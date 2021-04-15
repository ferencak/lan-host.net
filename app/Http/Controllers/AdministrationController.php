<?php

namespace App\Http\Controllers;
use DB;
use Kim\Activity\Activity;
use Illuminate\Http\Request;

class AdministrationController extends Controller
{
    
	public function getServicesCount()
	{

		return count(DB::table('services')->get());

	}

	public function getClientsCount()
	{

		return count(DB::table('clients')->get());

	}
	
	/**
	* Get online users
	*
	* @return int
	*/
	public function countOnlineUsers(){

		return count(DB::select(DB::raw('SELECT * FROM time_sessions WHERE last_activity > :current_time'), array(':current_time' => time())));
	}

	/**
    * Get all clients
    *
    * @return Clients
    */
    public function getClients()
    {

        return DB::table('clients')->get();
    
    }

    public function getClientByID($id)
    {
    	return DB::table('clients')->where('id', '=', $id)->get()[0];
    }

    public function countAllCredits()
    {
    	$clients = DB::table('clients')->get();
    	$credits = 0;
    	foreach($clients as $client){
    		$data = json_decode($client->data);
    		$credits += $data->client_data->balance;
    	}
    	return $credits;
    }
}
