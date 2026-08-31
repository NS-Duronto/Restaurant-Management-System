<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ItemCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) : array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->resource->getRawOriginal('name'),
            'slug'         => $this->slug,
            'description'  => $this->resource->getRawOriginal('description') ?? '',
            'status'       => $this->status,
            'thumb'        => $this->thumb,
            'cover'        => $this->cover,
            'translations' => $this->whenLoaded('translations', $this->translations),
        ];
    }
}
