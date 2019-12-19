<?php

namespace Aredfern\Invplan\Models;
Use Aredfern\Invplan\Models\APIClass;

/**
 * Class VariantVendor
 *
 * @package Aredfern\InvPlan
 */
class VariantVendor extends APIclass
{

    public function getById($id) {  
        $returned = $this->getResource("variant-vendors",$id);
        return $returned;
    }


}