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
    /** @var string The interface controls which API gets called by the class. For live Guzzle is injected in. */
    private $interface = null;

    public function __construct($interface) {
        $this->interface = $interface;
    }

    public function getById($id) {  
        $returned = $this->interface->getResource("variants/".$id);
        return $returned;
    }


}