<?php

namespace Andyredfern\Invplan;
use Andyredfern\Invplan\Interfaces\ApiInterface;
use Andyredfern\Invplan\Providers\VendorProvider;
use Andyredfern\Invplan\Models\Vendor;
use Andyredfern\Invplan\Models\SortConfig;
use PHPUnit\Framework\MockObject\MockObject;

class VendorProviderTest extends \PHPUnit\Framework\TestCase
{
    private static $VENDOR_RESPONSE = array(
        "vendor" => array(
            "id" => "v1",
            "barcode" => "123456789",
        ),
    );

    private $_vendorProvider;
    private $_mockApi;

    public function setUp():void
    {
        /* @var ApiInterface|MockObject $api */
        $api = $this->getMockBuilder(ApiInterface::class)->getMock();
        $this->_mockApi = $api;
        $this->_vendorProvider = new VendorProvider($this->_mockApi);
    }

    /**
     * If exists should return properly typed object.
     *
     * @test
     */
    public function getVendorThatExists()
    {
        // Given
        $this->_mockApi->expects($this->once())
            ->method('getResource')
            ->with($this->equalTo("vendors/v1"))
            ->will($this->returnValue(self::$VENDOR_RESPONSE));

        // When
        $result = $this->_vendorProvider->getById("v1");

        // Then
        $this->assertResult($result);
    }

    /**
     * If does not exist should throw error.
     *
     * @test
     */
    public function getVendorThatDoesNotExist()
    {
        // Given
        $getResponse = array(
            "result" => array(
                "status" => "error",
                "message" => "Failed to find vendor v2",
            ),
        );
        $this->_mockApi->expects($this->once())
            ->method('getResource')
            ->with($this->equalTo("vendors/v2"))
            ->will($this->returnValue($getResponse));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Failed to find vendor v2");

        // When
        $this->_vendorProvider->getById("v2");
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
                "name" => "vendors",
                "display_name" => "vendors",
                "total" => 2,
                "count" => 2,
                "limit" => VendorProvider::$PAGINATION_LIMIT,
                "page" => 0,
                "start" => 0,
                "end" => 1
            ),
            "vendors" => array(
                array("id" => "v1"),
                array("id" => "v2"),
            )
        );
        $expectedUrl = "vendors?fields=id&limit=".VendorProvider::$PAGINATION_LIMIT."&page=0";
        $this->_mockApi->expects($this->once())
            ->method('getResource')
            ->with($this->equalTo($expectedUrl))
            ->will($this->returnValue($response));

        // When
        $result = $this->_vendorProvider->getIds(array(), SortConfig::none());

        // Then
        $this->assertEquals($result, array("v1", "v2"));
    }

    /**
     * Should GET with correct url.
     *
     * @test
     */
    public function getIdsWithFiltersAndSort()
    {
        // Given
        $filter = array("sku" => "sku1", "test" => "test1");
        $sortConfig = new SortConfig("under_value", "desc");
        $response = array(
            "meta" => array(
                "name" => "vendors",
                "display_name" => "vendors",
                "total" => 2,
                "count" => 2,
                "limit" => VendorProvider::$PAGINATION_LIMIT,
                "page" => 0,
                "start" => 0,
                "end" => 1
            ),
            "vendors" => array(
                array("id" => "v1"),
                array("id" => "v2"),
            )
        );
        $expectedUrl = "vendors?fields=id&sku=sku1&test=test1&under_value_sort=desc&limit=".VendorProvider::$PAGINATION_LIMIT."&page=0";
        $this->_mockApi->expects($this->once())
            ->method('getResource')
            ->with($this->equalTo($expectedUrl))
            ->will($this->returnValue($response));

        // When
        $result = $this->_vendorProvider->getIds($filter, $sortConfig);

        // Then
        $this->assertEquals($result, array("v1", "v2"));
    }

    /**
     * Should get all pages
     *
     * @test
     */
    public function getIdsShouldPaginate()
    {
        // Given
        $filter = array("sku" => "sku1", "test" => "test1");
        $sortConfig = new SortConfig("under_value", "desc");
        $response1 = array(
            "meta" => array(
                "name" => "vendors",
                "display_name" => "vendors",
                "total" => 150,
                "count" => 100,
                "limit" => VendorProvider::$PAGINATION_LIMIT,
                "page" => 0,
                "start" => 0,
                "end" => 0
            ),
            "vendors" => array(
                array("id" => "v1"),
                array("id" => "v2"),
            )
        );
        $response2 = array(
            "meta" => array(
                "name" => "vendors",
                "display_name" => "vendors",
                "total" => 150,
                "count" => 50,
                "limit" => VendorProvider::$PAGINATION_LIMIT,
                "page" => 1,
                "start" => 0,
                "end" => 1
            ),
            "vendors" => array(
                array("id" => "po3"),
                array("id" => "po4"),
            )
        );
        $expectedUrl1 = "vendors?fields=id&sku=sku1&test=test1&under_value_sort=desc&limit=".VendorProvider::$PAGINATION_LIMIT."&page=0";
        $expectedUrl2 = "vendors?fields=id&sku=sku1&test=test1&under_value_sort=desc&limit=".VendorProvider::$PAGINATION_LIMIT."&page=1";
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
        $result = $this->_vendorProvider->getIds($filter, $sortConfig);

        // Then
        $this->assertEquals($result, array("v1", "v2", "po3", "po4"));
    }

    private function assertResult(Vendor $result)
    {
        $this->assertEquals($result->getId(), "v1");
        $this->assertEquals($result->barcode, "123456789");
    }

}
