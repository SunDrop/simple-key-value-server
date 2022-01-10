<?php

namespace KV;

class Storage implements StorageInterface
{
    private MemTableInterface $memTable;

    public function __construct()
    {
        $this->memTable = new MemTable();
    }

    public function set(string $key, string $val): void
    {
        $this->memTable->set($key, $val);
    }

    public function delete(string $key): void
    {
        /**
         * 1) Remove from MemTable
         * 2) Add to MemRemovedKeys
         */
    }

    public function get(string $key): string
    {
        /**
         * 1) Check if exist in MemRemovedKeys
         * 2) Check if exist in MemTable
         * 2.1) if exist end (opCount > limit) => make dump
         * 3) Read from SSTable
         */
    }

    public function isExist(string $key): bool
    {
        /**
         * 1) Check if exist in MemRemovedKeys
         * 2) Check if exist in MemTable
         * 3) Read from SSTable
         */
    }
}
