<?php

namespace Andyredfern\Invplan\Providers;
Use Andyredfern\Invplan\Providers\APIClass;

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
