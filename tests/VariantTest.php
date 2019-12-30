<?php

namespace Aredfern\Invplan;
use Aredfern\Invplan\Models\InvPlanAPI;

class VariantTest extends \PHPUnit\Framework\TestCase
{

    /** @var string Mock token used by mockbuilder */
    protected static $TOKEN = "123456789";
    
    /** @var string Mock token used by mockbuilder */
    protected static $ACCOUNT = "987654321";

    public function setUp():void {

    }

    /** @test  */
    public function get_a_variant_that_exists_by_id() {
        $getResponse = array("variant"=>array("sku"=>"123456"));

        $api = $this->getMockBuilder('Aredfern\Invplan\Models\InvPlanAPI')
            ->setConstructorArgs(array(self::$TOKEN, self::$ACCOUNT))
            ->getMock();

        $api->expects($this->once())
            ->method('getResource')
            ->will($this->returnValue($getResponse));

        $this->Variant = new Models\Variant($api);

        $testArray = $this->Variant->getById(1);
        $this->assertIsArray($testArray);
        $this->assertArrayHasKey("variant",$testArray);
    }
    
   

}