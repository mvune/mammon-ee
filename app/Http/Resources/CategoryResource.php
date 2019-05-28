<?php

namespace App\Http\Resources;

use App\Http\Resources\TransactionFilterResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'side' => [
                'code' => $this->side,
                'label' => __("misc.category_sides.{$this->side}"),
            ],
            'priority' => $this->priority,
            'rules' => TransactionFilterResource::collection($this->transactionFilters),
        ];
    }
}
