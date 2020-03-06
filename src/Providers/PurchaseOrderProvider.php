<?php

namespace Andyredfern\Invplan\Providers;

use Andyredfern\Invplan\Models\Items;
Use Andyredfern\Invplan\Models\PurchaseOrder;
use Andyredfern\Invplan\Models\PurchaseOrders;
use Andyredfern\Invplan\Models\SortConfig;

/**
 * Class PurchaseOrderProvider
 *
 * @package Andyredfern\InvPlan
 */
class PurchaseOrderProvider
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
        $ids = [];
        foreach ($this->getFields($filter, $sortConfig, ["id"]) as $po) {
            $ids[] = $po->getId();
        }
        return $ids;
    }

    public function getFields(array $filter, SortConfig $sortConfig, array $fields): PurchaseOrders
    {
        $baseUrl =  "purchase-orders?";

        if (!in_array("id", $fields)) {
            $fields[] = "id";
        }
        $baseUrl .= "fields=" . implode(",", $fields);

        if (!empty($filter)) {
            $filterUrl = http_build_query($filter);
            $baseUrl .= "&".$filterUrl;
        }

        if (!$sortConfig->isEmpty()) {
            $baseUrl .= "&" .$sortConfig->getUrlField()."=".$sortConfig->getDirection();
        }

        $purchaseOrders = new PurchaseOrders();
        $page = 0;
        $isLastPage = 0;

        while ($isLastPage == 0) {
            $response = $this->_interface->getResource($this->_appendPagination($baseUrl, $page));
            foreach ($response["purchase-orders"] as $purchaseOrder) {
                $purchaseOrders[] = new PurchaseOrder($purchaseOrder);
            }
            $isLastPage = $response["meta"]["count"] < $response["meta"]["limit"];
            $page++;
        }

        return $purchaseOrders;
    }

    public function getById(string $id): PurchaseOrder
    {
        $response = $this->_interface->getResource("purchase-orders/".$id);
        return $this->_parseResponse($response);
    }

    public function applyPatch(string $id, PurchaseOrder $purchaseOrder): PurchaseOrder
    {
        $patch = array('purchase-order' => $purchaseOrder->getData());
        $response = $this->_interface->patchResource("purchase-orders/".$id, $patch);
        return $this->_parseResponse($response);
    }

    public function patchItems(string $id, Items $items): Items
    {
        $patch = array('items' => $items->expose());
        $response = $this->_interface->patchResource("purchase-orders/".$id."/items", $patch);
        return $this->_parseItemsResponse($response);
    }

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

    private function _parseResponse(array $response)
    {
        if (array_key_exists("result", $response) && $response["result"]["status"] == "error") {
            throw new \Exception(json_encode($response));
        }
        return new PurchaseOrder($response["purchase-order"]);
    }

    private function _parseItemsResponse(array $response)
    {
        if (array_key_exists("result", $response) && $response["result"]["status"] == "error") {
            throw new \Exception(json_encode($response));
        }
        return Items::fromUntyped($response["items"]);
    }

    private function _appendPagination(string $baseUrl, int $page): string
    {
        return $baseUrl."&limit=".self::$PAGINATION_LIMIT."&page=".$page;
    }
}
