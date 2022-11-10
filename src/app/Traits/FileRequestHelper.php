<?php

namespace Jensramakers\LaravelMediaLibrary\app\Traits;

trait FileRequestHelper
{
    public function getFilesInOrder($requestArray): array
    {
        $fileArray = [];
        $fileRequestIds = array_keys($requestArray ?? []);
        foreach ($fileRequestIds as $key => $fileRequestId) {
            $fileArray[$fileRequestId] = ['order' => $key +1];
        }

        return $fileArray;
    }
}