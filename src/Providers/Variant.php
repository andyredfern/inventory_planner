<?php

namespace Andyredfern\Invplan\Providers;
Use Andyredfern\Invplan\Providers\APIClass;

/**
 * Class Variant
 *
 * @package Andyredfern\InvPlan
 */
class Variant
{
    /**
     * 
     *
     * @var string The interface controls which API gets called by the class. For live Guzzle is injected in. 
     */
    private $interface = null;

    public function __construct($interface)
    {
        $this->interface = $interface;
    }

    public function getById($id)
    {  
        $returned = $this->interface->getResource("variants/".$id);
        return $returned;
    }

    public function applyPatch($id,$variant)
    {
        $returned = $this->interface->patchResource("variants/".$id, $variant);
        return $returned;
    }

    public function applyUpdate($id,$variant)
    {
        echo "Apply update<br>";
        echo $id;
        print_r($variant);
        $returned = $this->interface->putResource("variants/".$id, $variant);
        return $returned;
    }

    public function create($variant)
    {
        $returned = $this->interface->postResource("variants", $variant);
        return $returned;
    }



}
