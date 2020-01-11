<?php

namespace Aredfern\Invplan\Models;
Use Aredfern\Invplan\Models\APIClass;

/**
 * Class Variants
 *
 * @package Aredfern\InvPlan
 */
class Variants
{

    private $interface = null;


    public function __construct($interface) {
        $this->interface = $interface;
    }

    public function getCollection($fields, $sort_field, $sort_direction,$limit,$page,$filter) {
        $filter_url = http_build_query($filter);  
        $url =  "variants?fields=" . $fields . "&" .$sort_field."=".$sort_direction."&limit=".$limit."&page=".$page."&".$filter_url;
        $returned = $this->interface->getResource($url);
        return $returned;
    }


}