<?php

namespace App\Http\Controllers\api;

use App\Transaction;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostTransactionsRequest;
use App\Http\Resources\Transaction as TransactionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * @var \Illuminate\Support\Collection
     */
    private $accounts;

    private $accountsFetched = false;

    /**
     * Get a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return TransactionResource::collection(
            Auth::user()->transactions()
                ->when($request->get('accounts'), function ($query, $accounts) {
                    return $query->whereIn('account_id', explode(',', $accounts));
                })
                ->orderBy('serial_number', 'desc')
                ->paginate(50)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\PostTransactionsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostTransactionsRequest $request)
    {
        Transaction::insert($request->input('transactions'));
    }
}
