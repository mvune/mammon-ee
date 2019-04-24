<?php

namespace App\Http\Requests;

use App\Rules\UserMaxTransactions;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostTransactionsRequest extends FormRequest
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
            'bank' => 'required',
            'csv_file' => [
                'required',
                'file',
                'mimes:csv,txt',
                'max:12000',
                new UserMaxTransactions,
            ],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!$validator->failed()) {
                $this->formatCsv($validator);
            }
        });
    }

    /**
     * Format csv to array and add to request.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return bool
     */
    private function formatCsv($validator)
    {
        $csvFile = $this->file('csv_file')->path();
        $isFirstLine = true;
        $transactions = [];

        // Open file.
        if (($handle = fopen($csvFile, 'r')) !== false) {
            // Loop through lines.
            while (($data = fgetcsv($handle, 1000)) !== false) {
                // Skip first line.
                if ($isFirstLine) {
                    $isFirstLine = false;
                    continue;
                }

                $transaction = $this->mapRabobank($data);

                // Validate line.
                if ($transaction === null || Validator::make($transaction, (new CreateTransactionRequest)->rules())->fails()) {
                    $validator->errors()->add('csv_file', trans('validation.csv_invalid_format'));
                    break;
                }

                $this->formatFields($transaction);
                $this->linkToBankAccount($transaction);
                $transactions[] = $transaction;
            }
        } else {
            $validator->errors()->add('csv_file', trans('validation.csv_unreadable'));
        }

        fclose($handle);

        if ($validator->errors()->any()) {
            return false;
        }

        // Add formatted csv to request.
        $this->request->add(['transactions' => $transactions]);

        return true;
    }

    private function formatFields(&$trn)
    {
        $floatFields = ['amount', 'balance_after_transaction', 'original_amount', 'exchange_rate'];

        foreach ($floatFields as $field) {
            $trn[$field] = $this->castToFloat($trn[$field]);
        }

        $trn['created_at'] = Carbon::now();
        $trn['updated_at'] = Carbon::now();
    }

    private function linkToBankAccount(&$trn)
    {
        $bankAccount = $this->getUserBankAccounts()->first(function ($bankAccount) use ($trn) {
            return $bankAccount->iban === strtoupper(str_replace(' ', '', $trn['iban']));
        });

        if ($bankAccount) {
            $trn['account_id'] = $bankAccount->id;
        } else {
            $trn['account_id'] = Auth::user()->bankAccounts()->create([
                'name' => null,
                'iban' => $trn['iban'],
            ])->id;
            $this->bankAccountsFetched = false;
        }

        unset($trn['iban']);
    }

    /**
     * Get cached bank accounts of current authenticated user.
     * 
     * @return \Illuminate\Support\Collection
     */
    private function getUserBankAccounts()
    {
        if (!$this->bankAccountsFetched) {
            $this->bankAccounts = Auth::user()->bankAccounts()->get();
            $this->bankAccountsFetched = true;
        }

        return $this->bankAccounts;
    }

    /**
     * Cast value to float.
     * 
     * @return float
     */
    private function castToFloat($value)
    {
        if ($value !== '' && gettype($value) === 'string') {
            return floatval(str_replace(',', '.', $value));
        }

        if (gettype($value) === 'double') {
            return $value;
        }
        
        return null;
    }

    /**
     * Map csv line to associative array (Rabobank).
     *
     * @param array $data
     * @return array|null
     */
    private function mapRabobank(array $data)
    {
        if (count($data) != 26) {
            return null;
        }

        $t = [];

        list(
            $t['iban'],
            $t['currency'],
            $t['bic'],
            $t['serial_number'],
            $t['date'],
            $t['value_date'],
            $t['amount'],
            $t['balance_after_transaction'],
            $t['counterparty_iban'],
            $t['counterparty_name'],
            $t['opposing_party'],
            $t['initiating_party'],
            $t['counterparty_bic'],
            $t['code'],
            $t['batch_id'],
            $t['reference'],
            $t['mandate_code'],
            $t['creditor_id'],
            $t['payment_code'],
            $t['description_1'],
            $t['description_2'],
            $t['description_3'],
            $t['reason_return'],
            $t['original_amount'],
            $t['original_currency'],
            $t['exchange_rate'],
        ) = $data;

        return $t;
    }
}
