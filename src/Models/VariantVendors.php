<?php

namespace Andyredfern\Invplan\Models;
Use Andyredfern\Invplan\Models\APIClass;

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
