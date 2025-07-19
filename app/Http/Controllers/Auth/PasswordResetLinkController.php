<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OTPCode;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
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
     * Search a password reset by e-mail or phone number
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkEmailOrPhone(Request $request)
    {
        $random_int_stringified = (string) random_int(1000000, 9999999);

        if (is_numeric($request->data)) {
            $password_reset = PasswordReset::where('phone', $request->data)->first();
            $user = User::where('phone', $request->data)->first();
            // $basic  = new \Vonage\Client\Credentials\Basic(config('vonage.api_key'), config('vonage.api_secret'));
            // $client = new \Vonage\Client($basic);

            if (is_null($user)) {
                // return redirect()->back()->with('error_message', __('notifications.find_user_404'));
                throw ValidationException::withMessages([
                    'data' => __('notifications.find_user_404'),
                ]);
            }

            if (is_null($password_reset)) {
                // return redirect()->back()->with('error_message', __('notifications.find_password_reset_404'));
                throw ValidationException::withMessages([
                    'data' => __('notifications.find_password_reset_404'),
                ]);
            }

            $password_reset->update([
                'token' => $random_int_stringified,
                'updated_at' => now()
            ]);

            session()->put('phone', $password_reset->phone);

            // try {
            //     $client->sms()->send(new \Vonage\SMS\Message\SMS($password_reset->phone, 'DikiTivi', (string) $password_reset->token));

            // } catch (\Throwable $th) {
            //     $response_error = json_decode($th->getMessage(), false);

            //     return $this->handleError($response_error, __('notifications.create_user_SMS_failed'), 500);
            // }

            return redirect()->back()->with('success_message', __('notifications.token_sent'));

        } else {
            $password_reset = PasswordReset::where('email', $request->data)->first();
            $user = User::where('email', $request->data)->first();

            if (is_null($user)) {
                // return redirect()->back()->with('error_message', __('notifications.find_user_404'));
                throw ValidationException::withMessages([
                    'data' => __('notifications.find_user_404'),
                ]);
            }

            if (is_null($password_reset)) {
                // return redirect()->back()->with('error_message', __('notifications.find_password_reset_404'));
                throw ValidationException::withMessages([
                    'data' => __('notifications.find_password_reset_404'),
                ]);
            }

            $password_reset->update([
                'token' => $random_int_stringified,
                'updated_at' => now()
            ]);

            session()->put('email', $password_reset->email);

            Mail::to($password_reset->email)->send(new OTPCode($password_reset->token));

            return redirect()->back()->with('success_message', __('notifications.token_sent'));
        }
    }

    /**
     * Check the password reset token validity.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkToken(Request $request)
    {
        if (trim($request->token) == null) {
            return $this->handleError($request->token, __('validation.required'), 400);
        }

        if (session()->has('email')) {
            $user = User::where('email', session()->get('email'))->first();
            $password_reset = PasswordReset::where('email', session()->get('email'))->first();

            if (is_null($user)) {
                // return redirect()->back()->with('error_message', __('notifications.find_user_404'));
                throw ValidationException::withMessages([
                    'token' => __('notifications.find_user_404'),
                ]);
            }

            if (is_null($password_reset)) {
                // return redirect()->back()->with('error_message', __('notifications.find_password_reset_404'));
                throw ValidationException::withMessages([
                    'token' => __('notifications.find_password_reset_404'),
                ]);
            }

            if ($password_reset->token != $request->token) {
                // return redirect()->back()->with('error_message', __('notifications.bad_token'));
                throw ValidationException::withMessages([
                    'token' => __('notifications.bad_token'),
                ]);
            }

            $user->update([
                'email_verified_at' => now(),
                'updated_at' => now(),
            ]);

            session()->put('former_password', $password_reset->former_password);

            return redirect()->route('password.reset')->with('success_message', __('auth.reset-password'));
        }

        if (session()->has('phone')) {
            $user = User::where('phone', session()->get('phone'))->first();
            $password_reset = PasswordReset::where('phone', session()->get('phone'))->first();

            $user = User::where('email', session()->get('email'))->first();
            $password_reset = PasswordReset::where('email', session()->get('email'))->first();

            if (is_null($user)) {
                // return redirect()->back()->with('error_message', __('notifications.find_user_404'));
                throw ValidationException::withMessages([
                    'token' => __('notifications.find_user_404'),
                ]);
            }

            if (is_null($password_reset)) {
                // return redirect()->back()->with('error_message', __('notifications.find_password_reset_404'));
                throw ValidationException::withMessages([
                    'token' => __('notifications.find_password_reset_404'),
                ]);
            }

            if ($password_reset->token != $request->token) {
                // return redirect()->back()->with('error_message', __('notifications.bad_token'));
                throw ValidationException::withMessages([
                    'token' => __('notifications.bad_token'),
                ]);
            }

            $user->update([
                'phone_verified_at' => now(),
                'updated_at' => now(),
            ]);

            session()->put('former_password', $password_reset->former_password);

            return redirect()->route('password.reset')->with('success_message', __('auth.reset-password'));
        }
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }
}
