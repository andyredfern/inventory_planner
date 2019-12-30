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



    public function getById($id) {  
        $returned = $this->interface->getResource("variant-vendors/".$id);
        return $returned;
    }


}