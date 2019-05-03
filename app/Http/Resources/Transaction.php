<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Transaction extends JsonResource
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
            'iban' => $this->iban,
            'currency' => $this->currency,
            'date' => $this->date,
            'value_date' => $this->value_date,
            'amount' => $this->amount,
            'balance_after_transaction' => $this->balance_after_transaction,
            'counterparty_iban' => $this->counterparty_iban,
            'counterparty_name' => $this->counterparty_name,
            'code' => [
                'value' => $this->code,
                'label' => __('banks.' . $this->bic . '.transaction_types.' . $this->code),
            ],
            'reference' => $this->reference,
            'payment_code' => $this->payment_code,
            'description_1' => $this->description_1,
        ];
    }
}
