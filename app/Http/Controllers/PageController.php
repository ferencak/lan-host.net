<?php

namespace App\Http\Controllers;
use DB;
use Kim\Activity\Activity;
use Illuminate\Http\Request;


class PageController extends Controller
{
   
    /**
    * Check if current page in Service list section is active
    *
    * @param int $page
    * @param string $draw
    * @return string
    */
    public function isActiveServiceList($page, $draw){
        $serviceController = new ServiceController();

        if($page == $serviceController->getPageCount($draw))
            return 'class="service-link-active"';

    }

}
