<?php

namespace App\Http\Controllers\App\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgetPassword;
use App\Models\User;
use App\Models\PasswordResetTokens;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Validation\Rules;
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
    // public function store(Request $request): RedirectResponse
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = User::where('email', $request->email)->first();
        // dd($email);
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
        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        // $status = Password::sendResetLink(
        //     $request->only('email')
        // );

        // return $status == Password::RESET_LINK_SENT
        //             ? back()->with('status', __($status))
        //             : back()->withInput($request->only('email'))
        //                     ->withErrors(['email' => __($status)]);
    }

    public function newPassword()
    {
        $id = $data['id'] = $_GET['id'];
        // dd($id);
        // Retrieve the password reset token from the database
        $passwordResetToken = PasswordResetTokens::where('token', $id)->first();

        // If token is not found, return with an error
        if (!$passwordResetToken) {
            return redirect('login')->withInput()->with('error', 'Link Expired!');
        }else{
            return view('auth.new-password', compact('data'));
        }
    }

    public function storeNewPassword(Request $request)
    {
        if(isset($request->new_password) && isset($request->confirm_password)){
           $newPss = $request->new_password;
           $cnfPss = $request->confirm_password;
           $id = $request->id;

           // Retrieve the password reset token from the database
           $passwordResetToken = PasswordResetTokens::where('token', $id)->first();

           // If token is not found, return with an error
           if (!$passwordResetToken) {
               return redirect('login')->withInput()->with('error', 'Link Expired!');
           }

           // Check if the token is within the valid time frame (5 minutes)
           $createdAt = Carbon::parse($passwordResetToken->created_at);
           $now = Carbon::now();
           if ($createdAt->diffInMinutes($now) > 5) {
               return redirect('login')->with('error', 'The token has expired!');
           }

           // Retrieve user details using email from the token
           $userDetail = User::where('email', $passwordResetToken->email)->first();

           if($newPss == $cnfPss) {
                $data = User::find($userDetail->id);
                $data->password = Hash::make($newPss);
                $data->save();
                // Delete the token after use
                PasswordResetTokens::where('email', $passwordResetToken->email)->delete();
                return redirect('login')->with('success', 'Password has been successfully updated. Now you can login with new password.');
            }else{
                return redirect('login')->with('error', 'New password and confirm password are not matched');
            }
        }else{
            return redirect()->back()->with('error', 'New password and confirm password are required');
        }
    }
}
