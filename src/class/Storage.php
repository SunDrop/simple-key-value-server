<?php

namespace KV;

class Storage implements StorageInterface
{
    private MemTableInterface $memTable;

    private RemovedKeysTableInterface $removedKeysTable;

    public function __construct()
    {
        $this->memTable = new MemTable();
        $this->removedKeysTable = new RemovedKeysTable();
    }

    public function set(string $key, string $val): void
    {
        $this->memTable->set($key, $val);
        $this->removedKeysTable->delete($key);
        /**
         * Todo:
         * 1) opCount++
         * 2) if opCount > Limit => dump SSTable
         */
    }

    public function delete(string $key): void
    {
        $this->memTable->delete($key);
        $this->removedKeysTable->add($key);
    }

    public function get(string $key): string
    {
        if ($this->removedKeysTable->isExist($key)) {
            throw new NotExistKeyException("Key $key was deleted");
        }
        if ($this->memTable->isExist($key)) {
            return $this->memTable->get($key);
        }
        /**
         * TODO: Read from SSTable
         */
        throw new NotExistKeyException("Key $key not exist");
    }

    public function isExist(string $key): bool
    {
        if ($this->memTable->isExist($key)) {
            return true;
        }
        if ($this->removedKeysTable->isExist($key)) {
            return false;
        }
        /**
         * TODO: Read from SSTable
         */
        return false;
    }
}
