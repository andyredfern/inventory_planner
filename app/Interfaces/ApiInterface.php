<?php

namespace Aredfern\Invplan\Interfaces;

interface ApiInterface {
    public function getResource($resourceType);
    public function patchResource($resourceType, $data_array);
    public function putResource($resourceType, $data_array);
    public function isAlive($phrase);
}