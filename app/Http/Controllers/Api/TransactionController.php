<?php

namespace App\Http\Controllers\api;

use App\Transaction;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostTransactionsRequest;
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Auth::user()->transactions()->get();
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
