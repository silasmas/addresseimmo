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
        $type = null;
        $icon = null;

        switch ($this->type) {
            case 'equipped_house':
                $type = 'Maison équipée';
                $icon = 'bi bi-house-heart';
                break;

            case 'empty_house':
                $type = 'Maison vide';
                $icon = 'bi bi-house';
                break;

            case 'unfinished_house':
                $type = 'Maison inachevée';
                $icon = 'bi bi-house-exclamation';
                break;

            case 'equipped_apartment':
                $type = 'Appartement équipé';
                $icon = 'bi bi-house';
                break;

            case 'empty_apartment':
                $type = 'Appartement vide';
                $icon = 'fa-solid fa-bed';
                break;

            case 'empty_plot':
                $type = 'Parcelle vide';
                $icon = 'fa-regular fa-square-full';
                break;

            case 'house_plot':
                $type = 'Concession maison';
                $icon = 'bi bi-house';
                break;

            default:
                $type = null;
                $icon = null;
                break;
        }

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
            'price' => formatIntegerNumber($this->price),
            'converted_price' => formatIntegerNumber($price),
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
            'readable_type' => $type,
            'readable_icon' => $icon,
            'user' => User::make($this->user),
            'category' => Category::make($this->category),
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
