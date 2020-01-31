<?php

namespace Andyredfern\Invplan\Models;

/**
 * Class Items
 *
 * @package Andyredfern\InvPlan
 */
class Items extends \ArrayIterator
{
    public function __construct(Item ...$items)
    {
        parent::__construct($items);
    }
    public function current() : Item
    {
        return parent::current();
    }
    public function offsetGet($offset) : Item
    {
        return parent::offsetGet($offset);
    }

    public function expose() : array
    {
        $items = array();
        foreach ($this as $item) {
            $items[] = $item->expose();
        }
        return $items;
    }

    public static function fromUntyped(array $itemArray): Items
    {
        $items = new Items();
        foreach ($itemArray as $item) {
            $items[] = new Item($item);
        }
        return $items;
    }
}
