<?php

namespace App\Http\Resources;

use App\Models\User as ModelsUser;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class Role extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'role_name' => $this->role_name,
            'role_name_fr' => $this->getTranslation('role_name', 'fr'),
            'role_name_en' => $this->getTranslation('role_name', 'en'),
            'role_description' => $this->role_description,
            'role_description_fr' => $this->getTranslation('role_description', 'fr'),
            'role_description_en' => $this->getTranslation('role_description', 'en'),
            'created_by' => !empty($this->created_by) ? ModelsUser::find($this->created_by) : $this->created_by,
            'updated_by' => !empty($this->updated_by) ? ModelsUser::find($this->updated_by) : $this->updated_by,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s')
        ];
    }
}
