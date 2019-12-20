<?php

namespace Aredfern\Invplan\Models;
Use Aredfern\Invplan\Models\APIClass;

/**
 * Class Variant
 *
 * @package Aredfern\InvPlan
 */
class Variant extends APIclass
{

    public function getById($id) {  
        $returned = $this->getResource("variants/".$id);
        return $returned;
    }


}