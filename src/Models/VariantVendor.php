<?php

namespace Aredfern\Invplan\Models;
Use Aredfern\Invplan\Models\APIClass;

/**
 * Class VariantVendor
 *
 * @package Aredfern\InvPlan
 */
class VariantVendor
{
    private $interface = null;


    public function __construct($interface) {
        $this->interface = $interface;
    }



    public function getById($id,$vendorId) {  
        $returned = $this->interface->getResource("variants/".$id."/vendor/" .$vendorId);
        return $returned;
    }


}