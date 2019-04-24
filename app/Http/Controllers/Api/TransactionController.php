<?php

namespace App\Http\Controllers\api;

use App\Transaction;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostTransactionsRequest;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * @var \Illuminate\Support\Collection
     */
    private $bankAccounts;

    private $bankAccountsFetched = false;

    /**
     * Get a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\PostTransactionsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostTransactionsRequest $request)
    {
        Transaction::insert($request->input('transactions'));
    }
}
