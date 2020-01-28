<?php

namespace Andyredfern\Invplan\Providers;
Use Andyredfern\Invplan\Providers\APIClass;

/**
 * Class VariantVendors
 *
 * @package Andyredfern\InvPlan
 */
class VariantVendors
{
    private $interface = null;


    public function __construct($interface)
    {
        $this->interface = $interface;
    }



    public function getById($id)
    {  
        $returned = $this->interface->getResource("variants/".$id."/vendors");
        return $returned;
    }


}
