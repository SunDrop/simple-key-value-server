<?php

namespace KV;

interface RemovedKeysTableInterface
{
    public function add(string $key): void;

    public function delete(string $key): void;

    public function isExist(string $key): bool;
}
