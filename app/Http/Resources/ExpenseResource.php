<?php

namespace App\Http\Resources;

use App\Libraries\AppLibrary;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'expense_category_id' => $this->expense_category_id,
            'category_name' => $this->category?->name ?? '',
            'title' => $this->title,
            'amount' => (float) $this->amount,
            'currency_amount' => AppLibrary::currencyAmountFormat($this->amount),
            'date' => AppLibrary::date($this->date),
            'raw_date' => $this->date ? date('Y-m-d', strtotime($this->date)) : '',
            'payment_method' => $this->payment_method,
            'payee_name' => $this->payee_name ?? '',
            'note' => $this->note ?? '',
            'user_id' => $this->user_id,
            'user_name' => $this->user?->name ?? '',
            'file' => $this->file,
        ];
    }
}
