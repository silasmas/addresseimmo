<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class User extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'surname' => $this->surname,
            'about_me' => $this->about_me,
            'gender' => $this->gender,
            'birthdate' => $this->birthdate,
            'age' => !empty($this->birthdate) ? $this->age() : null,
            'country' => $this->country,
            'city' => $this->city,
            'address_1' => $this->address_1,
            'address_2' => $this->address_2,
            'p_o_box' => $this->p_o_box,
            'currency' => !empty($this->currency) ? ($this->currency == 'USD' ? '$' : 'FC') : null,
            'email' => $this->email,
            'phone' => $this->phone,
            'email_verified_at' => $this->email_verified_at,
            'phone_verified_at' => $this->phone_verified_at,
            'username' => $this->username,
            'password' => $this->password,
            'remember_token' => $this->remember_token,
            'api_token' => $this->api_token,
            'avatar_url' => $this->avatar_url != null ? $this->avatar_url : getWebURL() . '/assets/img/user.png',
            'status' => $this->status,
            'has_product_in_unpaid_cart' => $this->hasProductInUnpaidCart($request->get('product_id')),
            'average_rating' => round($this->averageRating(), 0),
            'feedbacks' => CustomerFeedback::collection($this->receivedFeedbacks),
            'unpaid_orders' => $this->unpaidOrders(),
            'roles' => Role::collection($this->roles)->sortByDesc('created_at')->toArray(),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by
        ];
    }
}
