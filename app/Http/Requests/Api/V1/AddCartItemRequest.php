<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Valide l'ajout d'une offre au panier.
 */
class AddCartItemRequest extends FormRequest
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
      'product_id' => ['required', 'integer', 'exists:products,id'],
      'quantity' => ['nullable', 'integer', 'min:1'],
    ];
  }
}
