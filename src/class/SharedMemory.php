<?php

namespace KV;

trait SharedMemory
{
    public function initSharedMemory(&$resource, string $projectID, int $blockSize)
    {
        $key = ftok(__FILE__, $projectID);
        $resource = shmop_open($key, "c", 0600, $blockSize);

        return shmop_read($resource, 0, shmop_size($resource));
    }

    public function flushSharedMemory(\Shmop $resource, $data)
    {
        return shmop_write($resource, $data, 0);
    }
}
