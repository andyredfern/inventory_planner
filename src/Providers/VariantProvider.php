<?php

namespace Andyredfern\Invplan\Providers;
Use Andyredfern\Invplan\Models\Variant;
use Andyredfern\Invplan\Models\SortConfig;

/**
 * Class VariantProvider
 *
 * @package Andyredfern\InvPlan
 */
class VariantProvider
{

    /**
     * 
     *
     * @var string The interface controls which API gets called by the class. For live Guzzle is injected in. 
     */
    private $_interface;

    public static $PAGINATION_LIMIT = 100;

    public function __construct($interface)
    {
        $this->_interface = $interface;
    }

    public function getIds(array $filter, SortConfig $sortConfig): array
    {
        $baseUrl =  "variants?";

        $fields = "id";
        $baseUrl .= "fields=" . $fields;

        if (!empty($filter)) {
            $filterUrl = http_build_query($filter);
            $baseUrl .= "&".$filterUrl;
        }

        if (!$sortConfig->isEmpty()) {
            $baseUrl .= "&" .$sortConfig->getUrlField()."=".$sortConfig->getDirection();
        }

        $variantIds = array();
        $page = 0;
        $isLastPage = 0;

        while ($isLastPage == 0) {
            $response = $this->_interface->getResource($this->_appendPagination($baseUrl, $page));
            foreach ($response["variants"] as $variant) {
                $variantIds[] = $variant["id"];
            }
            $isLastPage = $response["meta"]["end"];
            $page++;
        }

        return $variantIds;
    }

    public function getById(string $id): Variant
    {
        $response = $this->_interface->getResource("variants/".$id);
        return $this->_parseResponse($response);
    }

    public function applyPatch(string $id, Variant $variant): Variant
    {
        $patch = array('variant' => $variant->getData());
        $response = $this->_interface->patchResource("variants/".$id, $patch);
        return $this->_parseResponse($response);
    }

    public function applyUpdate(string $id, Variant $variant): Variant
    {
        $update = array('variant' => $variant->getData());
        $response = $this->_interface->putResource("variants/".$id, $update);
        return $this->_parseResponse($response);
    }

    public function create(Variant $variant): Variant
    {
        $create = array('variant' => $variant->getData());
        $response = $this->_interface->postResource("variants", $create);
        return $this->_parseResponse($response);
    }

    private function _parseResponse(array $response): Variant
    {
        if (array_key_exists("result", $response)) {
            throw new \Exception(json_encode($response));
        }
        return new Variant($response["variant"]);
    }

    private function _appendPagination(string $baseUrl, int $page): string
    {
        return $baseUrl."&limit=".self::$PAGINATION_LIMIT."&page=".$page;
    }
}
