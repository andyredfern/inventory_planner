<?php

namespace Aredfern\Invplan\Models;
Use Aredfern\Invplan\Models\APIClass;

/**
 * Class PurchaseOrder
 *
 * @package Aredfern\InvPlan
 */
class PurchaseOrder extends APIclass
{

    public function getById($id) {  
        $returned = $this->getResource("purchase-orders",$id);
        return $returned;
    }


}