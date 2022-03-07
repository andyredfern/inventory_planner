<?php

namespace Andyredfern\Invplan\Models;

/**
 * Class Import
 *
 * @package Andyredfern\InvPlan
 */
class Import
{
    /**
     * Location for overloaded data.  
     */
    private $_data = array();

    private $_items;

    public function __construct($data)
    {
        $this->_data = $data;
        $this->_items = new Items(
            ...array_map(
                function ($item) {
                    return new Item($item);
                },
                $data["imports"] ?? array()
            )
        );
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

    public function getData(): array
    {
        return $this->_data;
    }

    public function getImports(): Imports
    {
        return $this->_imports;
    }

    public function getId()
    {
        return $this->_data["id"];
    }
}
