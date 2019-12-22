<?php

interface APIClass {
    public function getResource($resourceType);
    public function patchResource($resourceType, $data_array);
    public function putResource($resourceType, $data_array);
    public function isAlive($phrase);
}