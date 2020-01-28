<?php

namespace Andyredfern\Invplan;
use Andyredfern\Invplan\Providers\VariantProvider;
use Andyredfern\Invplan\Models\Variant;
use Andyredfern\Invplan\Models\SortConfig;

class VariantProviderTest extends \PHPUnit\Framework\TestCase
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

    private static $VARIANT_RESPONSE = array(
        "variant" => array(
            "id" => "v1",
            "barcode" => "123456789",
        ),
    );

    private $_variantProvider;
    private $_mockApi;

    private function getVariantProvider(): VariantProvider
    {
        return $this->_variantProvider;
    }

    public function setUp():void
    {
        $this->_mockApi = $this->getMockBuilder('Andyredfern\Invplan\Providers\InvPlanAPI')
            ->setConstructorArgs(array(self::$TOKEN, self::$ACCOUNT))
            ->getMock();
        $this->_variantProvider = new VariantProvider($this->_mockApi);
    }

    /**
     * If exists should return properly typed object.
     *
     * @test 
     */
    public function getVariantThatExists()
    {
        // Given
        $this->_mockApi->expects($this->once())
            ->method('getResource')
            ->with($this->equalTo("variants/v1"))
            ->will($this->returnValue(self::$VARIANT_RESPONSE));

        // When
        $result = $this->getVariantProvider()->getById("v1");

        // Then
        $this->assertResult($result);
    }

    /**
     * If does not exist should throw error.
     *
     * @test 
     */
    public function getVariantThatDoesNotExist()
    {
        // Given
        $getResponse = array(
            "result" => array(
                "status" => "error",
                "message" => "Failed to find variant v2",
            ),
        );
        $this->_mockApi->expects($this->once())
            ->method('getResource')
            ->with($this->equalTo("variants/v2"))
            ->will($this->returnValue($getResponse));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Failed to find variant v2");

        // When
        $this->getVariantProvider()->getById("v2");
    }
    
    /**
     * 
     *
     * @test 
     */
    public function patchAVariantThatExists()
    {
        // Given
        $variantArray = array('barcode'=>'1577019503','email' => "test@test.com");
        $expectedPatch = array('variant' => $variantArray);
        $variant = new Variant($variantArray);
        $this->_mockApi->expects($this->once())
            ->method('patchResource')
            ->with(
                $this->equalTo("variants/v1"),
                $this->equalTo($expectedPatch)
            )
            ->will($this->returnValue(self::$VARIANT_RESPONSE));

        // When
        $result = $this->getVariantProvider()->applyPatch("v1", $variant);

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
                "name" => "variants",
                "display_name" => "variants",
                "total" => 2,
                "count" => 2,
                "limit" => VariantProvider::$PAGINATION_LIMIT,
                "page" => 0,
                "start" => 0,
                "end" => 1
            ),
            "variants" => array(
                array("id" => "v1"),
                array("id" => "v2"),
            )
        );
        $expectedUrl = "variants?fields=id&limit=".VariantProvider::$PAGINATION_LIMIT."&page=0";
        $this->_mockApi->expects($this->once())
            ->method('getResource')
            ->with($this->equalTo($expectedUrl))
            ->will($this->returnValue($response));

        // When
        $result = $this->getVariantProvider()->getIds(array(), SortConfig::none());

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
                "name" => "variants",
                "display_name" => "variants",
                "total" => 2,
                "count" => 2,
                "limit" => VariantProvider::$PAGINATION_LIMIT,
                "page" => 0,
                "start" => 0,
                "end" => 1
            ),
            "variants" => array(
                array("id" => "v1"),
                array("id" => "v2"),
            )
        );
        $expectedUrl = "variants?fields=id&sku=sku1&test=test1&under_value_sort=desc&limit=".VariantProvider::$PAGINATION_LIMIT."&page=0";
        $this->_mockApi->expects($this->once())
            ->method('getResource')
            ->with($this->equalTo($expectedUrl))
            ->will($this->returnValue($response));

        // When
        $result = $this->getVariantProvider()->getIds($filter, $sortConfig);

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
                "name" => "variants",
                "display_name" => "variants",
                "total" => 150,
                "count" => 100,
                "limit" => VariantProvider::$PAGINATION_LIMIT,
                "page" => 0,
                "start" => 0,
                "end" => 0
            ),
            "variants" => array(
                array("id" => "v1"),
                array("id" => "v2"),
            )
        );
        $response2 = array(
            "meta" => array(
                "name" => "variants",
                "display_name" => "variants",
                "total" => 150,
                "count" => 50,
                "limit" => VariantProvider::$PAGINATION_LIMIT,
                "page" => 1,
                "start" => 0,
                "end" => 1
            ),
            "variants" => array(
                array("id" => "po3"),
                array("id" => "po4"),
            )
        );
        $expectedUrl1 = "variants?fields=id&sku=sku1&test=test1&under_value_sort=desc&limit=".VariantProvider::$PAGINATION_LIMIT."&page=0";
        $expectedUrl2 = "variants?fields=id&sku=sku1&test=test1&under_value_sort=desc&limit=".VariantProvider::$PAGINATION_LIMIT."&page=1";
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
        $result = $this->getVariantProvider()->getIds($filter, $sortConfig);

        // Then
        $this->assertEquals($result, array("v1", "v2", "po3", "po4"));
    }

    /**
     * Test against real input from api.
     *
     * @test 
     */
    public function getVariantIntegrationTest()
    {
        // Given
        $jsonString = file_get_contents("./tests/Providers/TestData/get-variant.json", true);
        $this->_mockApi->expects($this->once())
            ->method('getResource')
            ->with($this->equalTo("variants/1234"))
            ->will($this->returnValue(json_decode($jsonString, true)));

        // When
        $result = $this->getVariantProvider()->getById("1234");

        // Then
        $this->assertEquals($result->getId(), "1234");
        $this->assertEquals($result->barcode, "123456789");
    }

    private function assertResult(Variant $result)
    {
        $this->assertEquals($result->getId(), "v1");
        $this->assertEquals($result->barcode, "123456789");
    }

}
