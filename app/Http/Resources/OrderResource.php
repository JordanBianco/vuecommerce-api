<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'country' => $this->country,
            'city' => $this->city,
            'province' => $this->province,
            'address' => $this->address,
            'zipcode' => $this->zipcode,
            'phone' => $this->phone,
            'notes' => $this->notes,
            'total' => $this->total,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'archived_at' => $this->archived_at,
            'products' => $this->products,
            'quantity' => $this->whenPivotLoaded('product_order', function() {
                return $this->pivot->quantity;
            })
        ];
    }
}
