<?php

namespace Andyredfern\Invplan\Models;

/**
 * Class Item
 *
 * @package Andyredfern\InvPlan
 */
class Item
{
    /**
     * Location for overloaded data.
     */
    private $_data = array();

    public function __construct($data)
    {
        $this->_data = $data;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->_data)) {
            return $this->_data[$name];
        }
        return null;
    }

    public function getId()
    {
        return $this->_data["id"];
    }

    public function expose(): array
    {
        return $this->_data;
    }
}
