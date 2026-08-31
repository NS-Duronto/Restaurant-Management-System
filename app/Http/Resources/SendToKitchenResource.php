<?php

namespace App\Http\Resources;

use App\Libraries\AppLibrary;
use Illuminate\Http\Resources\Json\JsonResource;

class SendToKitchenResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'send_no' => $this->send_no,
            'date' => AppLibrary::date($this->date),
            'raw_date' => $this->date ? date('Y-m-d', strtotime($this->date)) : '',
            'user_id' => $this->user_id,
            'user_name' => $this->user?->name ?? '',
            'note' => $this->note ?? '',
            'total_items' => (int) $this->total_items,
        ];
    }
}
