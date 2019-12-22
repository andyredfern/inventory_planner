<?php

namespace Aredfern\Invplan\Models;
Use Aredfern\Invplan\Models\APIClass;

/**
 * Class Variant
 *
 * @package Aredfern\InvPlan
 */
class Variant
{
    private $interface = null;

    public function __construct($interface) {
        $this->interface = $interface;
    }

    public function getById($id) {  
        $returned = $this->interface->getResource("variants/".$id);
        return $returned;
    }


}