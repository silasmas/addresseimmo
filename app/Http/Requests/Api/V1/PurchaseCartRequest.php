<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Valide l'initiation d'un paiement FlexPay pour le panier.
 */
class PurchaseCartRequest extends FormRequest
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
      'transaction_type_id' => ['required', 'integer', 'in:1,2'],
      'amount' => ['required', 'numeric', 'min:1'],
      'currency' => ['required', 'string', 'in:USD,CDF'],
      'other_phone' => ['required_if:transaction_type_id,1', 'nullable', 'string', 'max:20'],
      'channel' => ['nullable', 'string', 'max:50'],
      'app_url' => ['required_if:transaction_type_id,2', 'nullable', 'url'],
    ];
  }
}
