<?php

namespace Aredfern\Invplan\Models;
Use Aredfern\Invplan\Models\APIClass;

/**
 * Class Variants
 *
 * @package Aredfern\InvPlan
 */
class Variants extends APIclass
{

    public function getCollection($fields, $sort_field, $sort_direction,$limit,$page) { 
        $url =  "variants?fields=" . $fields . "&" .$sort_field."=".$sort_direction."&limit=".$limit."&page=".$page;
        echo "URL " . $url;
        $returned = $this->getResource($url);
        return $returned;
    }


}