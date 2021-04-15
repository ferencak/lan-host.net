<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class TransactionController extends Controller
{
    
    private static $request;
    /**
    * Create transactions
    *
    * @param object $request
    * @return array 
    */
	public static function create($client, $amount, $action)
	{

		return DB::table('transactions')->insert(['client' => $client, 'amount' => $amount, 'action' => $action, 'time' => date("d.m.Y H:i:s")]);

    }

    /**
    * Get all transactions from user
    *
    * @param int $limit
    * @return array
    */
    public static function get($limit)
    {

        return DB::table('transactions')->where('client', '=', ClientController::getID())->orderBy('id', 'desc')->limit(($limit == '*') ? 0 : $limit)->get();

	}

}
