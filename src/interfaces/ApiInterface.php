<?php
/**
 * OutputFieldTest PHPUnit Tests for OutputField class
 * 
 * Requires PHP version 7
 * 
 * @category Tests
 * @package  Andyredfern\Fixedwidth
 * @author   Andy Redfern <and@redfern.it>
 * @license  MIT License (MIT)
 * @link     https://github.com/andyredfern/fixedwidth
 */
namespace Andyredfern\Invplan\Interfaces;

interface ApiInterface
{
    public function getResource($resourceType);
    public function patchResource($resourceType, $data_array);
    public function putResource($resourceType, $data_array);
    public function isAlive($phrase);
}
