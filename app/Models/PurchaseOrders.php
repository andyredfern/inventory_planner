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


    public function get($fields=null,$sort=null,$limit=null,$page=null) {  
        
        $returned = $this->interface->getResource("purchase-orders/".$id);
        return $returned;
    }


}