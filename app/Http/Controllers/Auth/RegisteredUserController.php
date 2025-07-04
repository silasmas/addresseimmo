<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Models\Role;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register', ['countries' => showCountries()]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $random_int_stringified = (string) random_int(1000000, 9999999);

        // Validate fields
        $request->validate([
            'firstname' => ['required', 'string', 'max:255'],
            // 'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'firstname.required' => __('validation.required'),
            // 'email.required' => __('validation.required'),
            // 'email.email' => __('validation.custom.email.incorrect'),
            // 'email.unique' => __('validation.custom.email.exists'),
            'password.required' => __('validation.required'),
            'password.confirmed' => __('validation.confirmed'),
        ]);

        // Register user
        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'surname' => $request->surname,
            'about_me' => $request->about_me,
            'gender' => $request->gender,
            'birthdate' => isset($request->birthdate) ? explode('/', $request->birthdate)[2] . '-' . explode('/', $request->birthdate)[1] . '-' . explode('/', $request->birthdate)[0] : null,
            'country' => $request->country,
            'city' => $request->city,
            'address_1' => $request->address_1,
            'address_2' => $request->address_2,
            'p_o_box' => $request->p_o_box,
            'currency' => !empty($request->currency) ? $request->currency : 'USD',
            'email' => $request->email,
            'phone' => $request->phone,
            'username' => $request->username,
            'status' => !empty($request->status) ? $request->status : 'activated',
            'password' => Hash::make($request->password),
        ]);

        // Register password reset data
        PasswordReset::create([
            'email' => $request->email,
            'phone' => $request->phone,
            'token' => $random_int_stringified,
            'former_password' => $request->password
        ]);

        $role_member = null;
        $role_admin_exists = Role::where('role_name->fr', 'Administrateur')->exists();
        $role_member_exists = Role::where('role_name->fr', 'Membre')->exists();

        if (!$role_admin_exists) {
            // Add "Administrateur" role (the first role)
            Role::create([
                'role_name' => [
                    'en' => 'Administrator',
                    'fr' => 'Administrateur',
                ],
                'role_description' => [
                    'en' => 'Responsible for managing the operation of the platform.',
                    'fr' => 'Responsable de la gestion du fonctionnement de la plateforme.',
                ]
            ]);
        }

        if (!$role_member_exists) {
            // Add "Membre" role
            $role_member = Role::create([
                'role_name' => [
                    'en' => 'Member',
                    'fr' => 'Membre',
                ],
                'role_description' => [
                    'en' => 'Person who orders products or services published on the platform.',
                    'fr' => 'Personne qui commande des produits ou des services publiés sur la plateforme.',
                ]
            ]);

        } else {
            $role_member = Role::where('role_name->fr', 'Membre')->first();
        }

        // Register user with role
        $user->roles()->attach($role_member->id, ['is_selected' => 1]);

        if (isset($request->image_64)) {
            // $extension = explode('/', explode(':', substr($request->image_64, 0, strpos($request->image_64, ';')))[1])[1];
            $replace = substr($request->image_64, 0, strpos($request->image_64, ',') + 1);
            // Find substring from replace here eg: data:image/png;base64,
            $image = str_replace($replace, '', $request->image_64);
            $image = str_replace(' ', '+', $image);
            // Create image URL
            $image_path = 'images/users/' . $user->id . '/avatar/' . Str::random(50) . '.png';

            // Upload image
            Storage::disk('public')->put($image_path, base64_decode($image));

            $user->update([
                'avatar_url' => Storage::url($image_path),
                'updated_at' => now()
            ]);
        }

        // The API token
        $token = $user->createToken('auth_token')->plainTextToken;

        $user->update([
            'api_token' => $token,
            'updated_at' => now()
        ]);

        auth()->login($user);

        return redirect('/account')->with('success_message', __('miscellaneous.welcome_title', ['user' => $user->firstname . ' ' . $user->lastname]));
    }
}
