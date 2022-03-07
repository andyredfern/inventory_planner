<?php

namespace Andyredfern\Invplan\Providers;
Use Andyredfern\Invplan\Models\Import;
Use Andyredfern\Invplan\Models\Imports;
use Andyredfern\Invplan\Models\SortConfig;

/**
 * Class ImportsProvider
 *
 * @package Andyredfern\InvPlan
 */
class ImportsProvider
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
        $baseUrl =  "imports?";

        $fields = "id";
        $baseUrl .= "fields=" . $fields;

        if (!empty($filter)) {
            $filterUrl = http_build_query($filter);
            $baseUrl .= "&".$filterUrl;
        }

        if (!$sortConfig->isEmpty()) {
            $baseUrl .= "&" .$sortConfig->getUrlField()."=".$sortConfig->getDirection();
        }

        $importsIds = array();
        $page = 0;
        $isLastPage = 0;

        while ($isLastPage == 0) {
            $response = $this->_interface->getResource($this->_appendPagination($baseUrl, $page));
            foreach ($response["imports"] as $import) {
                $importsIds[] = $import["id"];
            }
            $isLastPage = $response["meta"]["end"];
            $page++;
        }

        return $importsIds;
    }

    public function getById(string $id): Import
    {
        $response = $this->_interface->getResource("imports/".$id);
        return $this->_parseResponse($response);
    }

    public function deleteById(string $id): Array
    {
        $response = $this->_interface->deleteResource("imports", $id);
        return $response;
    }

    public function postFileResource($id, $fileName): Array
    {
        $response = $this->_interface->postFileResource($id, $fileName);
        var_dump($response);
        return $this->_parseFileResponse($response);
    }

    public function completeImport($id, $fieldData): Array
    {
        $response = $this->_interface->postResource($id, $fieldData);
        var_dump($response);
        return $this->_parseResponse($response);
    }

    /*
    public function applyUpdate(string $id, PurchaseOrder $purchaseOrder): PurchaseOrder
    {
        $update = array('purchase-order' => $purchaseOrder->getData());
        $response = $this->_interface->putResource("purchase-orders/".$id, $update);
        return $this->_parseResponse($response);
    }

    public function create(PurchaseOrder $purchaseOrder): PurchaseOrder
    {
        $create = array('purchase-order' => $purchaseOrder->getData());
        $response = $this->_interface->postResource("purchase-orders", $create);
        return $this->_parseResponse($response);
    }
    */
    private function _parseResponse(array $response)
    {
        if (array_key_exists("result", $response) && $response["result"]["status"] == "error") {
            throw new \Exception(json_encode($response));
        }
        return $response["import"];
    }
    private function _parseFileResponse(array $response)
    {
        if (array_key_exists("result", $response) && $response["result"]["status"] == "error") {
            throw new \Exception(json_encode($response));
        }
        return $response["files"];
    }

    private function _appendPagination(string $baseUrl, int $page): string
    {
        return $baseUrl."&limit=".self::$PAGINATION_LIMIT."&page=".$page;
    }
}
