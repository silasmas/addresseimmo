<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\Api\V1\OtpSendRequest;
use App\Http\Requests\Api\V1\OtpVerifyRequest;
use App\Http\Requests\Api\V1\RegisterRequest;
use App\Http\Resources\UserProfile;
use App\Models\PasswordReset;
use App\Models\Role;
use App\Models\User;
use App\Services\OtpAuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

/**
 * Authentification par OTP (email ou telephone) pour le frontend Next.js.
 */
class OtpAuthController extends BaseController
{
  /**
   * @param OtpAuthService $otpService Service OTP
   */
  public function __construct(private readonly OtpAuthService $otpService)
  {
  }

  /**
   * Inscrit un utilisateur puis envoie un OTP de validation.
   *
   * @param RegisterRequest $request Donnees d'inscription
   * @return JsonResponse
   */
  public function register(RegisterRequest $request): JsonResponse
  {
    $roleMember = Role::firstOrCreate(
      ['role_name' => 'Membre'],
      ['role_description' => 'Personne qui commande des produits ou des services publies sur la plateforme.']
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

    $delivery = $this->otpService->sendOtp($user, $request->password);
    $login = $user->email ?? $user->phone ?? $user->username;

    return $this->handleResponse([
      'requires_otp' => true,
      'login' => $login,
      'channel' => $delivery['channel'],
      'masked_contact' => $delivery['masked'],
      'debug_otp' => $delivery['debug_otp'] ?? null,
    ], __('notifications.token_sent'));
  }

  /**
   * Envoie un OTP pour la connexion d'un utilisateur existant.
   *
   * @param OtpSendRequest $request Identifiant de connexion
   * @return JsonResponse
   */
  public function send(OtpSendRequest $request): JsonResponse
  {
    $user = $this->otpService->findUserByLogin($request->login);

    if ($user === null) {
      return $this->handleError(__('notifications.find_user_404'), [], 404);
    }

    if ($user->status === 'disabled') {
      return $this->handleError(__('notifications.account_disabled'), [], 403);
    }

    $delivery = $this->otpService->sendOtp($user);

    return $this->handleResponse([
      'requires_otp' => true,
      'login' => $request->login,
      'channel' => $delivery['channel'],
      'masked_contact' => $delivery['masked'],
      'debug_otp' => $delivery['debug_otp'] ?? null,
    ], __('notifications.token_sent'));
  }

  /**
   * Verifie l'OTP et retourne un token Sanctum.
   *
   * @param OtpVerifyRequest $request Identifiant et code OTP
   * @return JsonResponse
   */
  public function verify(OtpVerifyRequest $request): JsonResponse
  {
    $user = $this->otpService->findUserByLogin($request->login);

    if ($user === null) {
      return $this->handleError(__('notifications.find_user_404'), [], 404);
    }

    if (!$this->otpService->verifyOtp($user, $request->otp)) {
      return $this->handleError(__('notifications.bad_token'), [], 422);
    }

    $token = $this->issueToken($user, $request->input('device_name', 'nextjs'));

    return $this->handleResponse([
      'user' => new UserProfile($user->load('roles')),
      'token' => $token,
      'token_type' => 'Bearer',
    ], __('notifications.login_user_success'));
  }

  /**
   * Cree un token Sanctum et le synchronise sur le modele utilisateur.
   *
   * @param User $user Utilisateur authentifie
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
