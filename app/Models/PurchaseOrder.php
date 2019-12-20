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
        $returned = $this->getResource("purchase-orders/".$id);
        return $returned;
    }

    public function applyPatch($id,$PurchaseOrder) {
        $returned = $this->patchResource("purchase-orders/".$id,$PurchaseOrder);
        return $returned;
    }

    public function applyUpdate($id,$PurchaseOrder) {
        echo "Apply update<br>";
        echo $id;
        echo $PurchaseOrder;
        $returned = $this->putResource("purchase-orders/".$id, $PurchaseOrder);
        return $returned;
    }

    public function create($PurchaseOrder) {
        $returned = $this->postResource("purchase-orders",$PurchaseOrder);
        return $returned;
    }





}