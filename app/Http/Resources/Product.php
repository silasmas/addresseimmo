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
        return [
            'id' => $this->id,
            'product_name' => $this->product_name,
            'product_description' => $this->product_description,
            'quantity' => $this->quantity,
            'price' => formatDecimalNumber($this->price),
            'type' => $this->type,
            'action' => $this->action,
            'is_shared' => $this->is_shared,
            'category' => Category::make($this->category),
            'user' => User::make($this->user),
            'photos' => collect($this->photos)->map(function ($photo) {
                return [
                    'id' => $photo->id,
                    'file_name' => $photo->file_name,
                    'file_url' => $photo->file_url,
                ];
            }),
            'videos' => collect($this->videos)->map(function ($video) {
                return [
                    'id' => $video->id,
                    'file_name' => $video->file_name,
                    'file_url' => $video->file_url,
                ];
            }),
            'audios' => collect($this->audios)->map(function ($audio) {
                return [
                    'id' => $audio->id,
                    'file_name' => $audio->file_name,
                    'file_url' => $audio->file_url,
                ];
            }),
            'documents' => collect($this->documents)->map(function ($document) {
                return [
                    'id' => $document->id,
                    'file_name' => $document->file_name,
                    'file_url' => $document->file_url,
                ];
            }),
            'icon' => $this->icon,
            'image_url' => $this->image_url,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by
        ];
    }
}
