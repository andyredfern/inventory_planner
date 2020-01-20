<?php

namespace Andyredfern\Invplan;
use Andyredfern\Invplan\Models\InvPlanAPI;

class PurchaseOrderTest extends \PHPUnit\Framework\TestCase
{

    /**
     * 
     *
     * @var string Mock token used by mockbuilder 
     */
    protected static $TOKEN = "123456789";
    
    /**
     * 
     *
     * @var string Mock token used by mockbuilder 
     */
    protected static $ACCOUNT = "987654321";

    public function setUp():void
    {

    }

    /**
     * 
     *
     * @test 
     */
    public function getPurchaseOrderThatExists()
    {
        $getResponse = array("purchase-order"=>array("type"=>"po"));

        $api = $this->getMockBuilder('Andyredfern\Invplan\Models\InvPlanAPI')
            ->setConstructorArgs(array(self::$TOKEN, self::$ACCOUNT))
            ->getMock();

        $api->expects($this->once())
            ->method('getResource')
            ->will($this->returnValue($getResponse));

        $this->PurchaseOrder = new Models\PurchaseOrder($api);

        $testArray = $this->PurchaseOrder->getById(1);
        $this->assertIsArray($testArray);
        $this->assertArrayHasKey("purchase-order", $testArray);
    }
    
    /**
     * 
     *
     * @test 
     */
    public function patch_a_purchase_order_that_exists()
    {

        $patchResponse = array("purchase-order"=>array("type"=>"po"));

        $api = $this->getMockBuilder('Andyredfern\Invplan\Models\InvPlanAPI')
            ->setConstructorArgs(array(self::$TOKEN, self::$ACCOUNT))
            ->getMock();

        $api->expects($this->once())
            ->method('patchResource')
            ->will($this->returnValue($patchResponse));

        $this->PurchaseOrder = new Models\PurchaseOrder($api);
        $po_patch_array=array('purchase-order'=>array('expected_date'=>1577019503,'email' => "test@test.com"));
        $testArray = $this->PurchaseOrder->applyPatch(1, $po_patch_array);
        $this->assertIsArray($testArray);
        $this->assertArrayHasKey("purchase-order", $testArray);
    }

    /**
     * 
     *
     * @test 
     */
    public function put_a_purchase_order_that_exists()
    {

        $putResponse = array("purchase-order"=>array("type"=>"po"));

        $api = $this->getMockBuilder('Andyredfern\Invplan\Models\InvPlanAPI')
            ->setConstructorArgs(array(self::$TOKEN, self::$ACCOUNT))
            ->getMock();

        $api->expects($this->once())
            ->method('putResource')
            ->will($this->returnValue($putResponse));

        $this->PurchaseOrder = new Models\PurchaseOrder($api);
        $po_put_array=array('purchase-order'=>array('expected_date'=>1577019503,'email' => "test@test.com"));
        $testArray = $this->PurchaseOrder->applyUpdate(1, $po_put_array);
        $this->assertIsArray($testArray);
        $this->assertArrayHasKey("purchase-order", $testArray);
    }

    /**
     * 
     *
     * @test 
     */
    public function create_a_new_purchase_order()
    {

        $new_purchase_order = array("purchase-order"=>array(
                "status"=>"OPEN",
                "reference"=>"MY_PO_21",
                "expected_date"=>strtotime("now"),
                "vendor"=> "vendor_1",
                "warehouse" => "warehouee_1",
                "variants_filter"=>array(
                    "vendor"=>"liforme",
                    "replenishment_gt"=>'3'
                )
            )
        );

        $postResponse = array("purchase-order"=>array("type"=>"po"));

        $api = $this->getMockBuilder('Andyredfern\Invplan\Models\InvPlanAPI')
            ->setConstructorArgs(array(self::$TOKEN, self::$ACCOUNT))
            ->getMock();

        $api->expects($this->once())
            ->method('postResource')
            ->will($this->returnValue($postResponse));

        $this->PurchaseOrder = new Models\PurchaseOrder($api);
        $po_put_array=array('purchase-order'=>array('expected_date'=>1577019503,'email' => "test@test.com"));
        $testArray = $this->PurchaseOrder->create($new_purchase_order);
        $this->assertIsArray($testArray);
        $this->assertArrayHasKey("purchase-order", $testArray);
    }

}
