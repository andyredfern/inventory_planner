<?php

namespace Aredfern\Invplan\Models;
Use Aredfern\Invplan\Models\APIClass;

/**
 * Class VariantVendors
 *
 * @package Aredfern\InvPlan
 */
class VariantVendors
{
    private $interface = null;


    public function __construct($interface) {
        $this->interface = $interface;
    }



    public function getById($id) {  
        $returned = $this->interface->getResource("variants/".$id."/vendors");
        return $returned;
    }


}