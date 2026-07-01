<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Valide la demande d'envoi d'un code OTP.
 */
class OtpSendRequest extends FormRequest
{
  /**
   * Autorise toute requete publique d'envoi OTP.
   *
   * @return bool
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Regles de validation.
   *
   * @return array<string, mixed>
   */
  public function rules(): array
  {
    return [
      'login' => ['required', 'string', 'max:255'],
    ];
  }
}
