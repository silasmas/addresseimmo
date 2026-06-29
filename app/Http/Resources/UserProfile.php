<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Représentation publique d'un utilisateur pour l'API (sans données sensibles).
 */
class UserProfile extends JsonResource
{
  /**
   * Transforme la ressource en tableau JSON.
   *
   * @param Request $request Requête HTTP
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    $currencySymbol = match ($this->currency) {
      'USD' => '$',
      'CDF' => 'FC',
      default => null,
    };

    return [
      'id' => $this->id,
      'firstname' => $this->firstname,
      'lastname' => $this->lastname,
      'surname' => $this->surname,
      'fullname' => $this->fullname,
      'email' => $this->email,
      'phone' => $this->phone,
      'username' => $this->username,
      'avatar_url' => $this->avatar_url ?? getWebURL() . '/assets/img/user.png',
      'country' => $this->country,
      'city' => $this->city,
      'currency' => $this->currency,
      'readable_currency' => $currencySymbol,
      'status' => $this->status,
      'selected_role' => $this->selected_role,
      'roles' => Role::collection($this->whenLoaded('roles')),
      'average_rating' => round($this->averageRating() ?? 0, 1),
      'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
    ];
  }
}
