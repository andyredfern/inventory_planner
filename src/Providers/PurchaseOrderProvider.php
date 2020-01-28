<?php

namespace Andyredfern\Invplan\Providers;
Use Andyredfern\Invplan\Providers\APIClass;
Use Andyredfern\Invplan\Models\PurchaseOrder;

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
    private $interface;


    public function __construct($interface)
    {
        $this->interface = $interface;
    }

    public function getIds(array $filter): array
    {
        // TODO
        return array();
    }

    public function getById(string $id): PurchaseOrder
    {
        $response = $this->interface->getResource("purchase-orders/".$id);
        return $this->parseResponse($response);
    }

    public function applyPatch(string $id, PurchaseOrder $purchaseOrder): PurchaseOrder
    {
        $patch = array('purchase-order' => $purchaseOrder->getData());
        $response = $this->interface->patchResource("purchase-orders/".$id, $patch);
        return $this->parseResponse($response);
    }

    public function applyUpdate(string $id, PurchaseOrder $purchaseOrder): PurchaseOrder
    {
        $update = array('purchase-order' => $purchaseOrder->getData());
        $response = $this->interface->putResource("purchase-orders/".$id, $update);
        return $this->parseResponse($response);
    }

    public function create(PurchaseOrder $purchaseOrder): PurchaseOrder
    {
        $create = array('purchase-order' => $purchaseOrder->getData());
        $response = $this->interface->postResource("purchase-orders", $create);
        return $this->parseResponse($response);
    }

    private function parseResponse(array $response)
    {
        if (array_key_exists("result", $response)) {
            throw new \Exception(json_encode($response));
        }
        return new PurchaseOrder($response["purchase-order"]);
    }
}
