<?php

namespace App\Http\Controllers\Api;

use App\TransactionFilterTarget;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionFilterTargetResource;

class TransactionFilterTargetController extends Controller
{
    /**
     * Get a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TransactionFilterTargetResource::collection(TransactionFilterTarget::all());
    }
}
