<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class DiningTableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            "id"             => $this->id,
            "name"           => $this->name,
            "slug"           => $this->slug,
            "size"           => $this->size,
            "qr_code"        => asset($this->qr_code),
            "branch_id"      => $this->branch_id,
            "branch_name"    => optional($this->branch)->name,
            "status"              => $this->status,
            "dining_table_status" => (int) ($this->table_status ?? $this->dining_table_status ?? 1),
            "capacity"            => (int) ($this->capacity ?? $this->size ?? 4),
            "current_order_id"    => $this->current_order_id,
            "current_order"       => $this->currentOrder ? [
                "id"              => $this->currentOrder->id,
                "order_serial_no" => $this->currentOrder->order_serial_no,
                "token"           => $this->currentOrder->token,
                "total"           => $this->currentOrder->total,
                "subtotal"        => $this->currentOrder->subtotal,
                "discount"        => $this->currentOrder->discount,
                "payment_status"  => $this->currentOrder->payment_status,
                "status"          => $this->currentOrder->status,
                "customer_name"   => optional($this->currentOrder->user)->name ?? 'Customer',
                "order_datetime"  => $this->currentOrder->order_datetime,
            ] : null,
            "active_orders"       => $this->activeOrders ? $this->activeOrders->map(function ($order) {
                return [
                    "id"              => $order->id,
                    "order_serial_no" => $order->order_serial_no,
                    "token"           => $order->token,
                    "total"           => $order->total,
                    "subtotal"        => $order->subtotal,
                    "discount"        => $order->discount,
                    "payment_status"  => $order->payment_status,
                    "status"          => $order->status,
                    "customer_name"   => optional($order->user)->name ?? 'Customer',
                    "order_datetime"  => $order->order_datetime,
                ];
            }) : [],
            "qr"                  => $this->qr,
            "branch_address" => $this->branch->address,
            "branch_phone"   => $this->branch->phone,
        ];
    }
}