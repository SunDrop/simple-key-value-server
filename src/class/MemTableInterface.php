<?php

namespace KV;

interface MemTableInterface
{
    public function set(string $key, string $val): void;

    public function delete(string $key): void;

    public function get(string $key): string;

    public function isExist(string $key): bool;

    public function reset();
}
