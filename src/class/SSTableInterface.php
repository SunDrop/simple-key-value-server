<?php

namespace KV;

interface SSTableInterface
{
    public function dump(array $sortedData, array $removedKeys);

    public function get(string $key): string;

    public function isExist(string $key): bool;
}
