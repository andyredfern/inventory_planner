<?php

namespace Andyredfern\Invplan\Models;

/**
 * Class PurchaseOrder
 *
 * @package Andyredfern\InvPlan
 */
class PurchaseOrder
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
                $data["items"] ?? array()
            )
        );
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->_data)) {
            return $this->_data[$name];
        }
        return null;
    }

    public function getData(): array
    {
        return $this->_data;
    }

    public function getItems(): Items
    {
        return $this->_items;
    }

    public function getId()
    {
        return $this->_data["id"];
    }
}
