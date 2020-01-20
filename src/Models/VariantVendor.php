<?php

namespace Andyredfern\Invplan\Models;
Use Andyredfern\Invplan\Models\APIClass;

/**
 * Class VariantVendor
 *
 * @package Andyredfern\InvPlan
 */
class VariantVendor
{
    private $interface = null;


    public function __construct($interface)
    {
        $this->interface = $interface;
    }



    public function getById($id,$vendorId)
    {  
        $returned = $this->interface->getResource("variants/".$id."/vendor/" .$vendorId);
        return $returned;
    }


}
