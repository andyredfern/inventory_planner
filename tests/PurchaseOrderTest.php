<?php

namespace Aredfern\Invplan;

class PurchaseOrderTest extends \PHPUnit\Framework\TestCase
{

    protected $PurchaseOrder;

    public function setUp():void {
        $this->PurchaseOrder = new Models\PurchaseOrder();
    }

    /** @test  */
    public function get_a_purchase_order_that_exists() {
        $this->PurchaseOrder->getById(1);
        $this->assertEquals($this->PurchaseOrder->getById(1), 1);
    }

}