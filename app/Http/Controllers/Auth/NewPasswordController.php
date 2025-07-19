<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // User inputs
        $inputs = [
            'former_password' => session()->get('former_password'),
            'new_password' => $request->new_password,
            'confirm_new_password' => $request->confirm_new_password
        ];
        $user = session()->has('email') ? User::where('email', session()->get('email'))->first() : User::where('phone', session()->get('phone'))->first();

        if ($inputs['former_password'] == null) {
            // return redirect()->back()->with('error_message', __('validation.custom.former_password.incorrect'));
            throw ValidationException::withMessages([
                'new_password' => __('validation.custom.former_password.incorrect'),
            ]);
        }

        if ($inputs['new_password'] == null) {
            // return redirect()->back()->with('error_message', __('validation.custom.former_password.incorrect'));
            throw ValidationException::withMessages([
                'new_password' => __('validation.custom.former_password.incorrect'),
            ]);
        }

        if ($inputs['confirm_new_password'] == null) {
            // return redirect()->back()->with('error_message', __('notifications.confirm_new_password'));
            throw ValidationException::withMessages([
                'new_password' => __('notifications.confirm_new_password'),
            ]);
        }

        if (Hash::check($inputs['former_password'], $user->password) == false) {
            // return redirect()->back()->with('error_message', __('auth.password'));
            throw ValidationException::withMessages([
                'new_password' => __('auth.password'),
            ]);
        }

        if ($inputs['confirm_new_password'] != $inputs['new_password']) {
            // return redirect()->back()->with('error_message', __('notifications.confirm_new_password'));
            throw ValidationException::withMessages([
                'confirm_new_password' => __('notifications.confirm_new_password'),
            ]);
        }

        // if (preg_match('#^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$#', $inputs['new_password']) == 0) {
        //     return $this->handleError($inputs['new_password'], __('validation.custom.new_password.incorrect'), 400);
        // }

        // Update password reset
        if (!empty($user->email) and !empty($user->phone)) {
            $password_reset = PasswordReset::where([['email', $user->email], ['phone', $user->phone]])->first();
            $random_int_stringified = (string) random_int(1000000, 9999999);

            // If password_reset doesn't exist, create it.
            if ($password_reset == null) {
                PasswordReset::create([
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'token' => $random_int_stringified,
                    'former_password' => $inputs['new_password'],
                ]);
            }

            // If password_reset exists, update it
            if ($password_reset != null) {
                $password_reset->update([
                    'token' => $random_int_stringified,
                    'former_password' => $inputs['new_password'],
                    'updated_at' => now(),
                ]);
            }

        } else {
            if (!empty($user->email)) {
                $password_reset = PasswordReset::where('email', $user->email)->first();
                $random_int_stringified = (string) random_int(1000000, 9999999);

                // If password_reset doesn't exist, create it.
                if ($password_reset == null) {
                    PasswordReset::create([
                        'email' => $user->email,
                        'token' => $random_int_stringified,
                        'former_password' => $inputs['new_password'],
                    ]);
                }

                // If password_reset exists, update it
                if ($password_reset != null) {
                    $password_reset->update([
                        'token' => $random_int_stringified,
                        'former_password' => $inputs['new_password'],
                        'updated_at' => now(),
                    ]);
                }
            }

            if (!empty($user->phone)) {
                $password_reset = PasswordReset::where('phone', $user->phone)->first();
                $random_int_stringified = (string) random_int(1000000, 9999999);

                // If password_reset doesn't exist, create it.
                if ($password_reset == null) {
                    PasswordReset::create([
                        'phone' => $user->phone,
                        'token' => $random_int_stringified,
                        'former_password' => $inputs['new_password'],
                    ]);
                }

                // If password_reset exists, update it
                if ($password_reset != null) {
                    $password_reset->update([
                        'token' => $random_int_stringified,
                        'former_password' => $inputs['new_password'],
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // update "password" and "password_visible" column
        $user->update([
            'password' => Hash::make($inputs['new_password']),
            'updated_at' => now(),
        ]);

        // If the user is a parent, change its children's password according to its own
        if (!empty($user->parental_code)) {
            $children = User::where('belongs_to', $user->id)->get();

            foreach ($children as $child):
                $child->update([
                    'password' => $user->password,
                    'updated_at' => now(),
                ]);

                if (!empty($child->email)) {
                    $child_password_reset = PasswordReset::where('email', $child->email)->first();

                    $child_password_reset->update([
                        'former_password' => $inputs['password'],
                        'updated_at' => now(),
                    ]);

                } else {
                    if (!empty($child->phone)) {
                        $child_password_reset = PasswordReset::where('phone', $child->phone)->first();

                        $child_password_reset->update([
                            'former_password' => $inputs['password'],
                            'updated_at' => now(),
                        ]);
                    }
                }
            endforeach;
        }

        if (session()->has('email')) session()->forget('email');
        if (session()->has('phone')) session()->forget('phone');

        session()->forget('former_password');

        return redirect()->route('login')->with('success_message', __('notifications.update_password_success'));
    }
}
