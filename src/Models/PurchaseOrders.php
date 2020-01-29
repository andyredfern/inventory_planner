<?php

namespace Andyredfern\Invplan\Models;

/**
 * Class PurchaseOrders
 *
 * @package Andyredfern\InvPlan
 */
class PurchaseOrders extends \ArrayIterator
{
    public function __construct(PurchaseOrder ...$purchaseOrders)
    {
        parent::__construct($purchaseOrders);
    }
    public function current() : PurchaseOrder
    {
        return parent::current();
    }
    public function offsetGet($offset) : PurchaseOrder
    {
        return parent::offsetGet($offset);
    }
}
