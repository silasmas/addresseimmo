<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class CustomerOrder extends JsonResource
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
            $price = $this->convertPriceAtThatTime($userCurrency);
            $subTotal = $this->subtotalPrice($userCurrency);
        } else {
            // Si l'utilisateur n'est pas connecté, retourner le prix d'origine
            $price = $this->price_at_that_time;
            $subTotal = $this->subtotalPrice();
        }

        return [
            'id' => $this->id,
            'price_at_that_time' => $this->price_at_that_time,
            'converted_price_at_that_time' => formatIntegerNumber($price),
            'currency' => !empty($this->currency) ? ($this->currency == 'USD' ? '$' : 'FC') : null,
            'quantity' => $this->quantity,
            'sub_total' => $subTotal,
            'readable_sub_total' => formatIntegerNumber($subTotal),
            'product' => Product::make($this->product),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'created_at_explicit' => explicitDate($this->created_at->format('Y-m-d H:i:s')),
            'updated_at_explicit' => explicitDate($this->updated_at->format('Y-m-d H:i:s')),
            'cart_id' => $this->cart_id
        ];
    }
}
