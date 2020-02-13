<?php

namespace Andyredfern\Invplan\Providers;

use Andyredfern\Invplan\Interfaces\ApiInterface;
use Andyredfern\Invplan\Models\SortConfig;
use Andyredfern\Invplan\Models\Vendor;
use Exception;

/**
 * Class VendorProvider
 *
 * @package Andyredfern\InvPlan
 */
class VendorProvider
{

    /**
     *
     *
     * @var string The interface controls which API gets called by the class. For live Guzzle is injected in.
     */
    private $_interface;

    public static $PAGINATION_LIMIT = 100;

    public function __construct(ApiInterface $interface)
    {
        $this->_interface = $interface;
    }

    public function getIds(array $filter, SortConfig $sortConfig): array
    {
        $baseUrl = "vendors?";

        $fields = "id";
        $baseUrl .= "fields=" . $fields;

        if (!empty($filter)) {
            $filterUrl = http_build_query($filter);
            $baseUrl .= "&" . $filterUrl;
        }

        if (!$sortConfig->isEmpty()) {
            $baseUrl .= "&" . $sortConfig->getUrlField() . "=" . $sortConfig->getDirection();
        }

        $vendorIds = array();
        $page = 0;
        $isLastPage = 0;

        while ($isLastPage == 0) {
            $response = $this->_interface->getResource($this->_appendPagination($baseUrl, $page));
            foreach ($response["vendors"] as $vendor) {
                $vendorIds[] = $vendor["id"];
            }
            $isLastPage = $response["meta"]["count"] < $response["meta"]["limit"];
            $page++;
        }

        return $vendorIds;
    }

    public function getById(string $id): Vendor
    {
        $response = $this->_interface->getResource("vendors/" . $id);
        return $this->_parseResponse($response);
    }


    private function _parseResponse(array $response): Vendor
    {
        if (array_key_exists("result", $response) && $response["result"]["status"] == "error") {
            throw new Exception(json_encode($response));
        }
        return new Vendor($response["vendor"]);
    }

    private function _appendPagination(string $baseUrl, int $page): string
    {
        return $baseUrl . "&limit=" . self::$PAGINATION_LIMIT . "&page=" . $page;
    }
}
