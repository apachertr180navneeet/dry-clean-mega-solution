<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgetPassword;
use App\Models\User;
use App\Models\PasswordResetTokens;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Carbon\Carbon;


class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = User::where('email', $request->email)->first();
        if($email)
        {
            $passwordresetemail = PasswordResetTokens::where('email', $request->email)->first();
            $token = Str::random(64);
            if ($passwordresetemail) {
                PasswordResetTokens::where('email', $email->email)->delete();
            }
            $dataInsert = [
                'email' => $email->email,
                'token' => $token,
            ];
            PasswordResetTokens::create($dataInsert);
            $data = [
                'message' => 'This is a test email.',
                'name' => $email->name,
                'url' => url('new-password').'?id='.$token,

            ];
            Mail::to($request->email)->send(new ForgetPassword($data));
            return redirect()->back()->with('success', 'Please check your email to set password');
        }else{
            return redirect()->back()->with('error', 'Email does not exist');
        }
    }

    public function newPassword()
    {
        $data['id'] = $_GET['id'];
        return view('auth.new-password', compact('data'));
    }

    public function storeNewPassword(Request $request)
    {
        // Dump all request data for debugging
        // dd($request->all());

        // Check if new_password and confirm_password are set
        if ($request->has(['new_password', 'confirm_password'])) {
            $newPss = $request->new_password;
            $cnfPss = $request->confirm_password;
            $token = $request->token;

            // Retrieve the password reset token from the database
            $passwordResetToken = PasswordResetTokens::where('token', $token)->first();

            // If token is not found, return with an error
            if (!$passwordResetToken) {
                return back()->withInput()->with('error', 'Invalid token!');
            }

            // Check if the token is within the valid time frame (5 minutes)
            $createdAt = Carbon::parse($passwordResetToken->created_at);
            $now = Carbon::now();
            if ($createdAt->diffInMinutes($now) > 5) {
                return redirect()->back()->with('error', 'The token has expired!');
            }

            // Retrieve user details using email from request
            $userDetail = User::where('email', $passwordResetToken->email)->first();


            // Check if new password and confirm password match
            if ($newPss === $cnfPss) {
                // Update user's password
                $user = User::findOrFail($userDetail->id);
                $user->password = Hash::make($newPss);
                $user->save();

                // Optionally, delete the token after use
                PasswordResetTokens::where('email', $passwordResetToken->email)->delete();

                return redirect('/login')->with('success', 'Password has been successfully updated. Now you can login with new password.');
            } else {
                return redirect()->back()->with('error', 'New password and confirm password do not match.');
            }
        } else {
            return redirect()->back()->with('error', 'New password and confirm password are required.');
        }
    }
}
