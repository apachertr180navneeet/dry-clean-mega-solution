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
}
