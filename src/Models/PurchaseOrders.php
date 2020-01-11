<?php

namespace Aredfern\Invplan\Models;
Use Aredfern\Invplan\Models\APIClass;

/**
 * Class PurchaseOrders
 *
 * @package Aredfern\InvPlan
 */
class PurchaseOrders
{
    private $interface = null;


    public function __construct($interface) {
        $this->interface = $interface;
    }


    public function getCollection($fields=null,$sort_field=null,$sort_direction=null,$limit=null,$page=null,$filter=null) {  
        $filter_url = http_build_query($filter);
        echo $filter_url."<br>";
        $url =  "purchase-orders?fields=" . $fields . "&" .$sort_field."=".$sort_direction."&limit=".$limit."&page=".$page."&".$filter_url;
        echo $url;
        $returned = $this->interface->getResource($url);
        return $returned;
    }


}