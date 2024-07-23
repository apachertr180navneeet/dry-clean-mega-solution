<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\PasswordResetTokens;
use App\Models\User;
use Carbon\Carbon;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     *
     * @param Request $request
     * @return View
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the incoming request
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Attempt to reset the user's password
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                // Trigger PasswordReset event
                event(new PasswordReset($user));
            }
        );

        // Redirect based on password reset status
        return $status == Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withInput($request->only('email'))
                    ->withErrors(['email' => __($status)]);
    }

    /**
     * Display the new password creation view.
     *
     * @return View
     */
    public function newPassword(): View
    {
        $data['id'] = $_GET['id'];
        return view('auth.new-password', compact('data'));
    }

    /**
     * Store the newly created password.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function storeNewPassword(Request $request): RedirectResponse
    {
        // Check if new_password and confirm_password are present
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

            // Retrieve user details using email from the token
            $userDetail = User::where('email', $passwordResetToken->email)->first();

            // Check if new password and confirm password match
            if ($newPss === $cnfPss) {
                // Update user's password
                $user = User::findOrFail($userDetail->id);
                $user->password = Hash::make($newPss);
                $user->save();

                // Delete the token after use
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
