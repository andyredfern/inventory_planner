<?php

namespace Aredfern\Invplan\Models;
Use Aredfern\Invplan\Models\APIClass;

/**
 * Class PurchaseOrders
 *
 * @package Aredfern\InvPlan
 */
class PurchaseOrders extends APIclass
{

    public function get($fields=null,$sort=null,$limit=null,$page=null) {  
        
        $returned = $this->getResource("purchase-orders/".$id);
        return $returned;
    }


}