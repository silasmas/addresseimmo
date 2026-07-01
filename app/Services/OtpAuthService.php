<?php

namespace App\Services;

use App\Mail\OTPCode;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Gere la generation, l'envoi et la verification des codes OTP (email/SMS).
 */
class OtpAuthService
{
  /**
   * Duree de validite d'un OTP en minutes.
   */
  private const OTP_TTL_MINUTES = 15;

  /**
   * Recherche un utilisateur par email, telephone ou identifiant.
   *
   * @param string $login Identifiant de connexion
   * @return User|null Utilisateur trouve
   */
  public function findUserByLogin(string $login): ?User
  {
    return User::where('email', $login)
      ->orWhere('phone', $login)
      ->orWhere('username', $login)
      ->first();
  }

  /**
   * Genere et envoie un OTP a l'utilisateur.
   *
   * @param User $user Utilisateur cible
   * @param string|null $formerPassword Mot de passe en clair pour stockage legacy
   * @return array{channel: string, masked: string, debug_otp?: string} Infos d'envoi
   */
  public function sendOtp(User $user, ?string $formerPassword = null): array
  {
    $token = (string) random_int(1000000, 9999999);
    $channel = $user->email ? 'email' : 'phone';
    $contact = $user->email ?? $user->phone ?? '';

    $this->upsertPasswordReset($user, $token, $formerPassword);

    if ($user->email) {
      try {
        Mail::to($user->email)->send(new OTPCode($token));
      } catch (\Throwable $exception) {
        Log::warning('OTP email non envoye', [
          'email' => $user->email,
          'error' => $exception->getMessage(),
        ]);
      }
    }

    Log::info('OTP AddressImmo genere', [
      'user_id' => $user->id,
      'channel' => $channel,
      'contact' => $contact,
      'otp' => $token,
    ]);

    $result = [
      'channel' => $channel,
      'masked' => $this->maskContact($contact, $channel),
    ];

    if (config('app.debug')) {
      $result['debug_otp'] = $token;
    }

    return $result;
  }

  /**
   * Verifie un code OTP pour un utilisateur.
   *
   * @param User $user Utilisateur cible
   * @param string $otp Code saisi
   * @return bool True si le code est valide
   */
  public function verifyOtp(User $user, string $otp): bool
  {
    $record = $this->findPasswordReset($user);

    if ($record === null) {
      return false;
    }

    if ($record->token !== trim($otp)) {
      return false;
    }

    if ($record->updated_at !== null && $record->updated_at->lt(now()->subMinutes(self::OTP_TTL_MINUTES))) {
      return false;
    }

    if ($user->email) {
      $user->update(['email_verified_at' => now()]);
    }

    if ($user->phone) {
      $user->update(['phone_verfied_at' => now()]);
    }

    return true;
  }

  /**
   * Cree ou met a jour l'enregistrement OTP legacy.
   *
   * @param User $user Utilisateur
   * @param string $token Code OTP
   * @param string|null $formerPassword Mot de passe optionnel
   */
  private function upsertPasswordReset(User $user, string $token, ?string $formerPassword): void
  {
    $payload = [
      'token' => $token,
      'former_password' => $formerPassword ?? '',
      'updated_at' => now(),
    ];

    if ($user->email) {
      PasswordReset::updateOrCreate(
        ['email' => $user->email],
        array_merge($payload, ['phone' => $user->phone])
      );

      return;
    }

    PasswordReset::updateOrCreate(
      ['phone' => $user->phone],
      $payload
    );
  }

  /**
   * Retrouve l'enregistrement OTP d'un utilisateur.
   *
   * @param User $user Utilisateur
   * @return PasswordReset|null Enregistrement OTP
   */
  private function findPasswordReset(User $user): ?PasswordReset
  {
    if ($user->email) {
      return PasswordReset::where('email', $user->email)->first();
    }

    if ($user->phone) {
      return PasswordReset::where('phone', $user->phone)->first();
    }

    return null;
  }

  /**
   * Masque un contact pour l'affichage (ex: j***@email.com).
   *
   * @param string $contact Email ou telephone
   * @param string $channel Canal d'envoi
   * @return string Contact masque
   */
  private function maskContact(string $contact, string $channel): string
  {
    if ($channel === 'email' && str_contains($contact, '@')) {
      [$local, $domain] = explode('@', $contact, 2);
      $visible = substr($local, 0, 1);

      return $visible . '***@' . $domain;
    }

    if (strlen($contact) <= 4) {
      return '****';
    }

    return str_repeat('*', strlen($contact) - 4) . substr($contact, -4);
  }
}
