<?php

namespace Aredfern\Invplan\Models;
Use Aredfern\Invplan\Models\APIClass;

/**
 * Class PurchaseOrder
 *
 * @package Aredfern\InvPlan
 */
class PurchaseOrder
{

    /** @var string The interface controls which API gets called by the class. For live Guzzle is injected in. */
    private $interface = null;


    public function __construct($interface) {
        $this->interface = $interface;
    }

    public function getById($id) {  
        $returned = $this->interface->getResource("purchase-orders/".$id);
        return $returned;
    }

    public function applyPatch($id,$PurchaseOrder) {
        $returned = $this->interface->patchResource("purchase-orders/".$id,$PurchaseOrder);
        return $returned;
    }

    public function applyUpdate($id,$PurchaseOrder) {
        $returned = $this->interface->putResource("purchase-orders/".$id, $PurchaseOrder);
        return $returned;
    }

    public function create($PurchaseOrder) {
        $returned = $this->interface->postResource("purchase-orders",$PurchaseOrder);
        return $returned;
    }
}