<?php

namespace Aredfern\Invplan;
use Aredfern\Invplan\Models\InvPlanAPI;

class PurchaseOrderTest extends \PHPUnit\Framework\TestCase
{

    protected $PurchaseOrder;

    public function setUp():void {

        $token = "123456789";
        $account = "987654321";

        $response = array("purchase-order"=>array("type"=>"po"));

        $api = $this->getMockBuilder('Aredfern\Invplan\Models\InvPlanAPI')
            ->setConstructorArgs(array($token, $account))
            ->getMock();

        $api->expects($this->once())
            ->method('getResource')
            ->will($this->returnValue($response));

        $this->PurchaseOrder = new Models\PurchaseOrder($api);
    }

    /** @test  */
    public function get_a_purchase_order_that_exists() {
        $testArray = $this->PurchaseOrder->getById(1);
        $this->assertIsArray($testArray);
        $this->assertArrayHasKey("purchase-order",$testArray);
    }

    


}