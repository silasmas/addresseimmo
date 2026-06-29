<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Http\Requests\Api\V1\RegisterRequest;
use App\Http\Resources\UserProfile;
use App\Models\PasswordReset;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Gère l'authentification Sanctum pour le frontend Next.js.
 */
class AuthController extends BaseController
{
  /**
   * Inscrit un nouvel utilisateur et retourne un token API.
   *
   * @param RegisterRequest $request Données validées
   * @return JsonResponse
   */
  public function register(RegisterRequest $request): JsonResponse
  {
    $roleMember = Role::firstOrCreate(
      ['role_name' => 'Membre'],
      ['role_description' => 'Personne qui commande des produits ou des services publiés sur la plateforme.']
    );

    $user = User::create([
      'firstname' => $request->firstname,
      'lastname' => $request->lastname,
      'email' => $request->email,
      'phone' => $request->phone,
      'username' => $request->username,
      'country' => $request->country,
      'city' => $request->city,
      'currency' => $request->currency ?? 'USD',
      'status' => 'activated',
      'password' => Hash::make($request->password),
    ]);

    $user->roles()->attach($roleMember->id, ['is_selected' => 1]);

    if ($request->filled('email') || $request->filled('phone')) {
      PasswordReset::create([
        'email' => $request->email,
        'phone' => $request->phone,
        'token' => (string) random_int(1000000, 9999999),
        'former_password' => $request->password,
      ]);
    }

    $token = $this->issueToken($user, $request->input('device_name', 'nextjs'));

    return $this->handleResponse([
      'user' => new UserProfile($user->load('roles')),
      'token' => $token,
      'token_type' => 'Bearer',
    ], __('notifications.create_user_success'));
  }

  /**
   * Connecte un utilisateur via email ou téléphone.
   *
   * @param LoginRequest $request Données validées
   * @return JsonResponse
   */
  public function login(LoginRequest $request): JsonResponse
  {
    $user = User::where('email', $request->login)
      ->orWhere('phone', $request->login)
      ->orWhere('username', $request->login)
      ->first();

    if ($user === null || !Hash::check($request->password, $user->password)) {
      return $this->handleError(__('auth.failed'), [], 401);
    }

    if ($user->status === 'disabled') {
      return $this->handleError(__('notifications.account_disabled'), [], 403);
    }

    $token = $this->issueToken($user, $request->input('device_name', 'nextjs'));

    return $this->handleResponse([
      'user' => new UserProfile($user->load('roles')),
      'token' => $token,
      'token_type' => 'Bearer',
    ], __('notifications.login_user_success'));
  }

  /**
   * Retourne le profil de l'utilisateur connecté.
   *
   * @param Request $request Requête HTTP
   * @return JsonResponse
   */
  public function me(Request $request): JsonResponse
  {
    $user = $request->user()->load('roles');

    return $this->handleResponse(
      new UserProfile($user),
      __('notifications.find_user_success')
    );
  }

  /**
   * Révoque le token courant.
   *
   * @param Request $request Requête HTTP
   * @return JsonResponse
   */
  public function logout(Request $request): JsonResponse
  {
    $request->user()->currentAccessToken()?->delete();

    return $this->handleResponse(null, __('notifications.logout_user_success'));
  }

  /**
   * Crée un token Sanctum et le synchronise sur le modèle utilisateur.
   *
   * @param User $user Utilisateur authentifié
   * @param string $deviceName Nom du client
   * @return string Token en clair
   */
  private function issueToken(User $user, string $deviceName): string
  {
    $token = $user->createToken($deviceName)->plainTextToken;

    $user->update([
      'api_token' => $token,
      'last_connection' => now(),
    ]);

    return $token;
  }
}
