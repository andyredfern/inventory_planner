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
}
