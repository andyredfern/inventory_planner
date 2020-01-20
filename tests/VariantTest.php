<?php

namespace Andyredfern\Invplan;
use Andyredfern\Invplan\Models\InvPlanAPI;

class VariantTest extends \PHPUnit\Framework\TestCase
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
    public function get_a_variant_that_exists_by_id()
    {
        $getResponse = array("variant"=>array("sku"=>"123456"));

        $api = $this->getMockBuilder('Andyredfern\Invplan\Models\InvPlanAPI')
            ->setConstructorArgs(array(self::$TOKEN, self::$ACCOUNT))
            ->getMock();

        $api->expects($this->once())
            ->method('getResource')
            ->will($this->returnValue($getResponse));

        $this->Variant = new Models\Variant($api);

        $testArray = $this->Variant->getById(1);
        $this->assertIsArray($testArray);
        $this->assertArrayHasKey("variant", $testArray);
    }
    
    /**
     * 
     *
     * @test 
     */
    public function patch_a_variant_that_exists()
    {
        $patchResponse = array("variant"=>array("sku"=>"123456"));

        $api = $this->getMockBuilder('Andyredfern\Invplan\Models\InvPlanAPI')
            ->setConstructorArgs(array(self::$TOKEN, self::$ACCOUNT))
            ->getMock();

        $api->expects($this->once())
            ->method('patchResource')
            ->will($this->returnValue($patchResponse));

        $this->Variant = new Models\Variant($api);
        $variant_patch_array=array('variant'=>array('barcode'=>5012345678901,'brand' => "Coca Cola"));

        $testArray = $this->Variant->applyPatch(1, $variant_patch_array);
        $this->assertIsArray($testArray);
        $this->assertArrayHasKey("variant", $testArray);
    }

    /**
     * 
     *
     * @test 
     */
    public function put_a_variant_that_exists()
    {
        $putResponse = array("variant"=>array("sku"=>"123456"));

        $api = $this->getMockBuilder('Andyredfern\Invplan\Models\InvPlanAPI')
            ->setConstructorArgs(array(self::$TOKEN, self::$ACCOUNT))
            ->getMock();

        $api->expects($this->once())
            ->method('putResource')
            ->will($this->returnValue($putResponse));

        $this->Variant = new Models\Variant($api);
        $variant_patch_array=array('variant'=>array('barcode'=>5012345678901,'brand' => "Coca Cola"));

        $testArray = $this->Variant->applyUpdate(1, $variant_patch_array);
        $this->assertIsArray($testArray);
        $this->assertArrayHasKey("variant", $testArray);
    }
   

}
