<?php

namespace Andyredfern\Invplan;
use Andyredfern\Invplan\Providers\InvPlanAPI;
use Andyredfern\Invplan\Providers\PurchaseOrderProvider;
use Andyredfern\Invplan\Models\PurchaseOrder;
use Andyredfern\Invplan\Models\Item;

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

    private static $PURCHASE_ORDER_RESPONSE = array(
        "purchase-order" => array(
            "id" => "po1",
            "type" => "po",
            "items" => array(
                array("id" => "item1")
            ),
        ),
    );

    private $_purchaseOrderProvider;
    private $_mockApi;

    private function getPurchaseOrderProvider(): PurchaseOrderProvider
    {
        return $this->_purchaseOrderProvider;
    }

    public function setUp():void
    {
        $this->_mockApi = $this->getMockBuilder('Andyredfern\Invplan\Providers\InvPlanAPI')
            ->setConstructorArgs(array(self::$TOKEN, self::$ACCOUNT))
            ->getMock();
        $this->_purchaseOrderProvider = new PurchaseOrderProvider($this->_mockApi);
    }

    /**
     * If exists should return properly typed object.
     *
     * @test 
     */
    public function getPurchaseOrderThatExists()
    {
        // Given
        $this->_mockApi->expects($this->once())
            ->method('getResource')
            ->with($this->equalTo("purchase-orders/po1"))
            ->will($this->returnValue(self::$PURCHASE_ORDER_RESPONSE));

        // When
        $result = $this->getPurchaseOrderProvider()->getById("po1");

        // Then
        $this->assertResult($result);
    }

    /**
     * If does not exist should throw error.
     *
     * @test 
     */
    public function getPurchaseOrderThatDoesNotExist()
    {
        // Given
        $getResponse = array(
            "result" => array(
                "status" => "error",
                "message" => "Failed to find purchase-order po2",
            ),
        );
        $this->_mockApi->expects($this->once())
            ->method('getResource')
            ->with($this->equalTo("purchase-orders/po2"))
            ->will($this->returnValue($getResponse));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Failed to find purchase-order po2");

        // When
        $this->getPurchaseOrderProvider()->getById("po2");
    }
    
    /**
     * 
     *
     * @test 
     */
    public function patch_a_purchase_order_that_exists()
    {
        // Given
        $purchaseOrderArray = array('expected_date'=>1577019503,'email' => "test@test.com");
        $expectedPatch = array('purchase-order' => $purchaseOrderArray);
        $purchaseOrder = new PurchaseOrder($purchaseOrderArray);
        $this->_mockApi->expects($this->once())
            ->method('patchResource')
            ->with(
                $this->equalTo("purchase-orders/po1"),
                $this->equalTo($expectedPatch)
            )
            ->will($this->returnValue(self::$PURCHASE_ORDER_RESPONSE));

        // When
        $result = $this->getPurchaseOrderProvider()->applyPatch("po1", $purchaseOrder);

        // Then
        $this->assertResult($result);
    }

    private function assertResult(PurchaseOrder $result)
    {
        $this->assertEquals($result->getId(), "po1");
        $this->assertEquals($result->type, "po");
        $this->assertEquals(count($result->getItems()), 1);
        $item = $result->getItems()[0];
        $this->assertEquals($result->getItems()[0]->getId(), "item1");
    }

}
