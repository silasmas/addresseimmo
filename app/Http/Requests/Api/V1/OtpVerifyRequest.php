<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Valide la verification d'un code OTP.
 */
class OtpVerifyRequest extends FormRequest
{
  /**
   * Autorise toute requete publique de verification OTP.
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
      'otp' => ['required', 'string', 'min:6', 'max:10'],
      'device_name' => ['nullable', 'string', 'max:255'],
    ];
  }
}
