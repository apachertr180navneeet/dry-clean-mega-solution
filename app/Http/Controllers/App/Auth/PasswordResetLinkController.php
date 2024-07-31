<?php

namespace App\Http\Controllers\App\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgetPassword;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Validation\Rules;

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
            $data = [
                'message' => 'This is a test email.',
                'name' => $email->name,
                'url' => url('new-password').'?id='.$email->id,

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
        $data['id'] = $_GET['id'];
        // dd($id);
        return view('auth.new-password', compact('data'));
    }

    public function storeNewPassword(Request $request)
    {
        if(isset($request->new_password) && isset($request->confirm_password)){
           $newPss = $request->new_password;
           $cnfPss = $request->confirm_password;
           $id = $request->id;

           if($newPss == $cnfPss) {
                $data = User::find($id);
                $data->password = Hash::make($newPss);
                $data->save();
                return redirect('login')->with('success', 'Password has been successfully updated. Now you can login with new password.');
            }else{
                return redirect('login')->with('error', 'New password and confirm password are not matched');
            }
            die;
        }else{
            return redirect('login')->with('error', 'New password and confirm password are required');
        }
    }
}
