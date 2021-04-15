<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderableController extends Controller
{
    
    private $request;
    private $isError = 'false|';

    private $languageController;
    public function __construct()
    {

      $applicationController = new ApplicationController();
      $this->languageController = $applicationController->languageController;
      $this->languageController->page('client_service');

    }

    /**
    * Get all services
    *
    * @param string $type Service type
    * @return json
    */

    public function getServices($type)
    {

        return \App\Orderable::where('data->service_data->type', '=', $type)->get();

    }

    /**
    * Get selected service
    *
    * @param string $name Service name
    */
    public function getService($name)
    {

        return \App\Orderable::where('data->service_info->name_url', '=', $name)->value('data');

    }

    /**
    * Check if service exist
    *
    * @param string $name Service name
    * @return bool
    */
    public function checkService($name)
    {

        if(\App\Orderable::where('data->service_info->name_url', '=', $name)->count() > 0){
            return true;
        }else{
            return false;
        }

    }

    /**
    * Create a new service
    * 
    * @param mixed $request Posted form data
    * @param string $service Selected service
    */
    public function create($request, $service)
    {

        $this->request = (object) $request;

        $package = $this->getPackage($service, [
            'ram' => $this->request->service_ram,
            'ssd' => $this->request->service_ssd
        ]);

        if($package == 'custom') {
            $price = $this->calculatePrice(['ram' => $this->request->service_ram, 'ssd' => $this->request->service_ssd]);
        }
        else {
             $serviceData = json_decode($this->getService($service), true)['service_data']['packages'][$package];
             $price = $serviceData['price'];
        }

        $master = \App\Master::where([['data->master_info->preferred', '=', $service], ['data->master_status->free_space', '>=', 1]])->orWhere('data->master_status->free_space', '>=', 1)->limit(1)->value('data');

        if(empty($master)){
            $this->isError = 'true|nedostatku místa.';
        }

        $masterid = json_decode($master, true)['master_info']['master_id'];

        $data = [
            'service_info' => [
                'owner' => \App\Http\Controllers\ClientController::getId(),
                'name' => $this->request->service_name,
                'service' => $service,
                'price' => $price,
                'paid' => false,
                'package' => $package,
                'unique_id' => hash('sha256', time())
            ],
            'service_params' => [
                'ram' => $this->request->service_ram,
                'ssd' => $this->request->service_ssd,
                'slots' => $this->request->service_slots
            ],
            'service_data' => [
                'master_id' => $masterid,
                'vm' => isset($this->request->service_vm) ? $this->request->service_vm : false,
                'core' => isset($this->request->service_core) ? $this->request->service_core : false,
                'extensions' => isset($this->request->service_extensions) ? 'true' : 'false'
            ]
        ];

       \App\Services::insert(['data' => json_encode($data)]);

       $serviceid = \App\Services::where('data->service_info->unique_id', '=', hash('sha256', time()))->value('id');

       $this->isError = 'false|' . $serviceid;
       
       /*
       * Response 
       */
       $isError = explode('|', $this->isError); // Code | Message
       if($isError[0] == 'true'){
            return 'error|' . $isError[1];
       } elseif($isError[0] == 'false'){
            return 'success|' . $isError[1];
       } else {
            return 'unkown|';
       }

    }

    /**
    * Get a selected package by params
    * 
    * @param string $service Selected service
    * @param array $params Service parameters
    * @return string
    */
    public function getPackage($service, $params)
    {

        $service_data = \App\Orderable::where('data->service_info->name_url', '=', $service)->value('data');
        $service = json_decode($service_data, true);
        $available_packages = ['small', 'medium', 'big'];
        $packages = $service['service_data']['packages'];
        if($packages['big']['params']['ram'] == $params['ram'] && $packages['big']['params']['ssd'] == $params['ssd']){
            return 'big';
        }elseif($packages['medium']['params']['ram'] == $params['ram'] && $packages['medium']['params']['ssd'] == $params['ssd']){
            return 'medium';
        }elseif($packages['small']['params']['ram'] == $params['ram'] && $packages['small']['params']['ssd'] == $params['ssd']){
            return 'small';
        }else{
            return 'custom';
        }

    }

    /**
    * Get order step for client
    *
    * @param int $step Current step
    * @return string
    */
    public function getOrderStep($step)
    {

        switch($step) {
            case 1:
                return $this->languageController->get('order->title1');
            break;
            case 2:
                return $this->languageController->get('order->title2');
            break;
            default:
                return redirect()->back();
            break;
        }

    }


    /**
    * Calculate service price
    *
    * @param array $params Service parametters
    * @return int
    */
    public function calculatePrice($params)
    {
        
        $priceRam = 0;
        $ramPrice = 0.03;
        for($i=0;$i<$params['ram'];$i++){
            $priceRam += ($params['ram'] * $ramPrice / $params['ram']);
        }    
        $priceSsd = 0;
        $ssdPrice = 0.005;
        for($i=0;$i<$params['ssd'];$i++){
            $priceSsd += ($params['ssd'] * $ssdPrice / $params['ssd']);
        }
        return round($priceRam + $priceSsd);

    }

}
