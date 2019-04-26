<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->isMethod('PUT')) {
            $account = $this->route('account');

            return $account && $account->user_id === Auth::user()->id;
        } else {
            return true;
        }

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'max:100',
            'iban' => 'required|max:34',
        ];
    }
}
