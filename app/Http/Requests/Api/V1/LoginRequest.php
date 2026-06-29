<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Valide les données de connexion API.
 */
class LoginRequest extends FormRequest
{
  /**
   * Détermine si l'utilisateur peut effectuer cette requête.
   *
   * @return bool
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Règles de validation.
   *
   * @return array<string, mixed>
   */
  public function rules(): array
  {
    return [
      'login' => ['required', 'string', 'max:255'],
      'password' => ['required', 'string'],
      'device_name' => ['nullable', 'string', 'max:255'],
    ];
  }
}
