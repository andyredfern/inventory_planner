<?php

namespace Andyredfern\Invplan;
use Andyredfern\Invplan\Providers\InvPlanAPI;
use Andyredfern\Invplan\Providers\PurchaseOrderProvider;
use Andyredfern\Invplan\Models\PurchaseOrder;
use Andyredfern\Invplan\Models\Item;
use Andyredfern\Invplan\Models\SortConfig;
use PhpOption\None;

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
    public function patchAPurchaseOrderThatExists()
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

    /**
     * Should GET with correct url.
     *
     * @test 
     */
    public function getIdsWithNoFiltersAndNoSort()
    {
        // Given
        $response = array(
            "meta" => array(
                "name" => "purchase-orders",
                "display_name" => "purchase orders",
                "total" => 2,
                "count" => 2,
                "limit" => PurchaseOrderProvider::$PAGINATION_LIMIT,
                "page" => 0,
                "start" => 0,
                "end" => 1
            ),
            "purchase-orders" => array(
                array("id" => "po1"),
                array("id" => "po2"),
            )
        );
        $expectedUrl = "purchase-orders?fields=id&limit=".PurchaseOrderProvider::$PAGINATION_LIMIT."&page=0";
        $this->_mockApi->expects($this->once())
            ->method('getResource')
            ->with($this->equalTo($expectedUrl))
            ->will($this->returnValue($response));

        // When
        $result = $this->getPurchaseOrderProvider()->getIds(array(), SortConfig::none());

        // Then
        $this->assertEquals($result, array("po1", "po2"));
    }

    /**
     * Should GET with correct url.
     *
     * @test 
     */
    public function getIdsWithFiltersAndSort()
    {
        // Given
        $filter = array("vendor" => "vendor1", "test" => "test1");
        $sortConfig = new SortConfig("under_value", "desc");
        $response = array(
            "meta" => array(
                "name" => "purchase-orders",
                "display_name" => "purchase orders",
                "total" => 2,
                "count" => 2,
                "limit" => PurchaseOrderProvider::$PAGINATION_LIMIT,
                "page" => 0,
                "start" => 0,
                "end" => 1
            ),
            "purchase-orders" => array(
                array("id" => "po1"),
                array("id" => "po2"),
            )
        );
        $expectedUrl = "purchase-orders?fields=id&vendor=vendor1&test=test1&under_value_sort=desc&limit=".PurchaseOrderProvider::$PAGINATION_LIMIT."&page=0";
        $this->_mockApi->expects($this->once())
            ->method('getResource')
            ->with($this->equalTo($expectedUrl))
            ->will($this->returnValue($response));

        // When
        $result = $this->getPurchaseOrderProvider()->getIds($filter, $sortConfig);

        // Then
        $this->assertEquals($result, array("po1", "po2"));
    }

    /**
     * Should get all pages
     *
     * @test 
     */
    public function getIdsShouldPaginate()
    {
        // Given
        $filter = array("vendor" => "vendor1", "test" => "test1");
        $sortConfig = new SortConfig("under_value", "desc");
        $response1 = array(
            "meta" => array(
                "name" => "purchase-orders",
                "display_name" => "purchase orders",
                "total" => 150,
                "count" => 100,
                "limit" => PurchaseOrderProvider::$PAGINATION_LIMIT,
                "page" => 0,
                "start" => 0,
                "end" => 0
            ),
            "purchase-orders" => array(
                array("id" => "po1"),
                array("id" => "po2"),
            )
        );
        $response2 = array(
            "meta" => array(
                "name" => "purchase-orders",
                "display_name" => "purchase orders",
                "total" => 150,
                "count" => 50,
                "limit" => PurchaseOrderProvider::$PAGINATION_LIMIT,
                "page" => 1,
                "start" => 0,
                "end" => 1
            ),
            "purchase-orders" => array(
                array("id" => "po3"),
                array("id" => "po4"),
            )
        );
        $expectedUrl1 = "purchase-orders?fields=id&vendor=vendor1&test=test1&under_value_sort=desc&limit=".PurchaseOrderProvider::$PAGINATION_LIMIT."&page=0";
        $expectedUrl2 = "purchase-orders?fields=id&vendor=vendor1&test=test1&under_value_sort=desc&limit=".PurchaseOrderProvider::$PAGINATION_LIMIT."&page=1";
        $this->_mockApi->expects($this->exactly(2))
            ->method('getResource')
            ->will(
                $this->returnValueMap(
                    array(
                    array($expectedUrl1, $response1),
                    array($expectedUrl2, $response2),
                    )
                )
            );

        // When
        $result = $this->getPurchaseOrderProvider()->getIds($filter, $sortConfig);

        // Then
        $this->assertEquals($result, array("po1", "po2", "po3", "po4"));
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
