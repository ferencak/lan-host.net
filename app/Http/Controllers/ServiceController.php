<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    
    public $id;

    /**
    * Set an id of managed server
    *
    * @param $id
    * @return void
    */
    public function setID($id)
    {

        return $this->id = $id;

    }

    /**
    * Remove an id of managed server
    *
    * @return void
    */
    public function removeID()
    {

        return $this->id = null;

    }

    /**
    * Get all client services
    * TODO: Clean this code
    *
    * @return Services
    */

    public function getServices($take = false, $limit = false, $orderBy = 'desc')
    {
        if($take != false && $limit == false){
            return DB::table('services')->where('data->service_info->owner', '=', \App\Http\Controllers\ClientController::getId())->orderBy('id', $orderBy)->skip(count($this->getServices()) - $take)->take($take)->get(); 

        } elseif($take == false && $limit != false){
            return DB::table('services')->where('data->service_info->owner', '=', \App\Http\Controllers\ClientController::getId())->orderBy('id', $orderBy)->limit($limit)->get();
        } elseif($take == false && $limit == false) {
            return DB::table('services')->where('data->service_info->owner', '=', \App\Http\Controllers\ClientController::getId())->orderBy('id', $orderBy)->get();
        } else {
            return DB::table('services')->where('data->service_info->owner', '=', \App\Http\Controllers\ClientController::getId())->orderBy('id', $orderBy)->skip(count($this->getServices()) - $take)->take($take)->get(); 
        }
    
    }

    /**
    * Get all services by page with limitation
    * TODO: Domplete 'detailed' case
    *
    * @param int $page
    * @return Services
    */
    public function getServicesByPage($draw, $page = false)
    {

        switch($draw)
        {

            case 'simple':

                if(!$page){
                    $services = [];
                    for($i=0;$i<(count($this->getServices()) / 5);$i++){
                        $services = array_slice(object_to_array($this->getServices()), $page * 5, $page * 5 + 5);

                    }
                    return $services;
                }
                else{ 
                    return $this->getServices();
                }

            break;
            case 'detailed':
            break;
        }

    }

    /**
    * Get service id by uniqueid 
    *
    * @param mixed $uniqueid
    * @return int service id
    */
    public function getId($uniqueid)
    {

        return \App\Services::where('data->service_info->unique_id', '=', $uniqueid)->value('id');
    
    }

    /**
    * Get service data by id
    *
    * @return data
    */
    public function getData()
    {

        return \App\Services::where('id', '=', $this->id)->get()[0];

    }

    /**
    * Get an data from json
    *
    * @param int $id
    * @param string $selector
    * @param string $data
    * @return Data
    */ 
    public function get($id, $selector, $data)
    {

        $data = \App\Services::where('id', '=', $id)->value('data');
        $data = json_decode($data);
        return $data[$selector][$data];

    }

    /**
    * Check if user is owner of defined server
    *
    * @param int $client_id
    * @param int $server_id
    * @return data
    */
    public function checkOwner($client_id, $server_id)
    {
        if(count(DB::table('services')->where([['data->service_info->owner', '=', $client_id], ['id', '=', $server_id]])) > 0){
            return true;
        }else{
            return false;
        }
    }

    /**
    * Install selected service
    *
    */
    public function install(){}

    /**
    * Get master server details by ID
    *
    * @param int $master_id
    * @return data
    */
    public function getMasterById($master_id)
    {
        return \App\Master::where('id', '=', $master_id)->value('data');
    }

    /**
    * Get pages count of owned services
    *
    * @return int
    */
    public function getPageCount($draw)
    {

        switch($draw)
        {

            case 'simple':
                return ceil(count($this->getServices()) / 5);
            break;
            case 'detailed':
                return round(count($this->getServices()) / 3);
            break;
            default: 
                redirect()->back();
                exit();
            break;

        }

    }

    
}