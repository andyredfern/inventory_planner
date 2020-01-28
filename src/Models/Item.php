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

        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE
        );
        return null;
    }

    public function getId()
    {
        return $this->_data["id"];
    }
}
