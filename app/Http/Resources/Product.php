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
        $current_user = $request->user();

        return [
            'id' => $this->id,
            'product_name' => $this->product_name,
            'product_description' => $this->product_description,
            'quantity' => $this->quantity,
            'price' => formatDecimalNumber($this->price),
            'currency' => !empty($this->currency) ? ($this->currency == 'USD' ? '$' : 'FC') : null,
            'type' => $this->type,
            'action' => $this->action,
            'is_shared' => $this->is_shared,
            'user' => User::make($this->user),
            'converted_price' => $current_user ? $this->convertPrice($current_user->currency) : null,
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
            'category_id' => $this->category_id
        ];
    }
}
