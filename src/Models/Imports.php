<?php

namespace Andyredfern\Invplan\Models;

/**
 * Class Imports
 *
 * @package Andyredfern\InvPlan
 */
class Imports extends \ArrayIterator
{
    public function __construct(Import ...$imports)
    {
        parent::__construct($imports);
    }
    public function current() : Import
    {
        return parent::current();
    }
    public function offsetGet($offset) : Import
    {
        return parent::offsetGet($offset);
    }
}