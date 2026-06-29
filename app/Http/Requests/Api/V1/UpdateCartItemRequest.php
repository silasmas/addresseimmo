<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Valide la mise à jour d'une ligne de panier.
 */
class UpdateCartItemRequest extends FormRequest
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
      'action' => ['required', Rule::in(['increment', 'decrement', 'update'])],
      'quantity' => ['required_if:action,update', 'integer', 'min:1'],
    ];
  }
}
