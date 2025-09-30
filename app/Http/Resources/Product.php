<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class Product extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray(Request $request): array
    {
        // Vérifier si l'utilisateur est connecté
        if ($user = auth()->user()) {
            // Si l'utilisateur est connecté, récupérer sa devise
            $userCurrency = $user->currency;
            // Convertir le prix en fonction de la devise de l'utilisateur
            $price = $this->convertPrice($userCurrency);
        } else {
            // Si l'utilisateur n'est pas connecté, retourner le prix d'origine
            $price = $this->price;
        }

        if (!empty($this->currency)) {
            if ($this->currency == 'USD') {
                $currency = '$';
            }

            if ($this->currency == 'CDF') {
                $currency = 'FC';
            }

        } else {
            $currency = $this->currency;
        }

        return [
            'id' => $this->id,
            'product_name' => $this->product_name,
            'product_description' => $this->product_description,
            'quantity' => $this->quantity,
            'price' => formatDecimalNumber($this->price),
            'readable_currency' => $currency,
            'action' => $this->action,
            'country' => $this->country,
            'city' => $this->city,
            'address' => $this->address,
            'municipality' => $this->municipality,
            'neighborhood' => $this->neighborhood,
            'street' => $this->street,
            'is_shared' => $this->is_shared,
            'type' => $this->type,
            'user' => User::make($this->user),
            'converted_price' => formatDecimalNumber($price),
            'photos' => File::collection($this->photos),
            'videos' => File::collection($this->videos),
            'audios' => File::collection($this->audios),
            'documents' => File::collection($this->documents),
            'average_rating' => $this->averageRating() == null ? 0 : round($this->averageRating()),
            'feedbacks' => CustomerFeedback::collection($this->receivedFeedbacks),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'category_id' => $this->category_id,
            'user_id' => $this->user_id
        ];
    }
}
