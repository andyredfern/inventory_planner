<?php

namespace Andyredfern\Invplan\Models;

/**
 * Class SortConfig
 *
 * @package Andyredfern\InvPlan
 */
class SortConfig
{
    private $_field;
    private $_direction;

    public function __construct(string $field, string $direction = "asc")
    {
        $this->_field = $field;
        $this->_direction = $direction;
    }

    public static function none(): SortConfig
    {
        return new SortConfig("", "");
    }

    public function isEmpty()
    {
        return $this->_field == "";
    }

    public function getField()
    {
        return $this->_field;
    }

    public function getUrlField()
    {
        return $this->_field."_sort";
    }

    public function getDirection()
    {
        return $this->_direction;
    }
}
