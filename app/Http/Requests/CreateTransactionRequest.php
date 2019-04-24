<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'iban' => 'max:34',
            'currency' => 'max:4',
            'bic' => 'max:11',
            'serial_number' => 'required|max:18',
            'date' => 'required',
            'value_date' => '',
            'amount' => 'required|regex:/^[+-]?\d{1,16}([,.]\d{1,2})?$/|max:18',
            'balance_after_transaction' => 'regex:/^[+-]?\d{1,16}([,.]\d{1,2})?$/|max:18',
            'counterparty_iban' => 'max:34',
            'counterparty_name' => 'max:70',
            'opposing_party' => 'max:70',
            'initiating_party' => 'max:70',
            'counterparty_bic' => 'max:15',
            'code' => 'max:4',
            'batch_id' => 'max:35',
            'reference' => 'max:35',
            'mandate_code' => 'max:35',
            'creditor_id' => 'max:35',
            'payment_code' => 'max:35',
            'description_1' => 'max:140',
            'description_2' => 'max:140',
            'description_3' => 'max:140',
            'reason_return' => 'max:75',
            'original_amount' => 'regex:/^[+-]?\d{1,16}([,.]\d{1,2})?$/|max:18',
            'original_currency' => 'max:11',
            'exchange_rate' => 'regex:/^[+-]?\d{1,7}([,.]\d{1,4})?$/|max:11',
        ];
    }
}
