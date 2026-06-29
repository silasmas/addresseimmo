<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

/**
 * Valide les données d'inscription API.
 */
class RegisterRequest extends FormRequest
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
      'firstname' => ['required', 'string', 'max:255'],
      'lastname' => ['nullable', 'string', 'max:255'],
      'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email'],
      'phone' => ['nullable', 'string', 'max:20', 'unique:users,phone'],
      'username' => ['nullable', 'string', 'max:255', 'unique:users,username'],
      'password' => ['required', 'confirmed', Rules\Password::defaults()],
      'currency' => ['nullable', 'in:USD,CDF'],
      'country' => ['nullable', 'string', 'max:255'],
      'city' => ['nullable', 'string', 'max:255'],
    ];
  }
}
