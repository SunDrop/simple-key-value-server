<?php

namespace KV;

class RemovedKeysTable implements RemovedKeysTableInterface
{
    use SharedMemory;

    private const MEM_BLOCK_SIZE = 16 * 1024 * 1024;

    public const PROJECT_ID_REMOVED_KEYS = 'r';

    /** @var \Shmop|false */
    private $memory;

    /** @var array */
    private $keys = [];

    public function __construct()
    {
        $keys = $this->initSharedMemory(
            $this->memory,
            self::PROJECT_ID_REMOVED_KEYS,
            self::MEM_BLOCK_SIZE
        );

        if (!$this->keys = @unserialize($keys)) {
            $this->keys = [];
        }
    }

    public function __destruct()
    {
        @shmop_close($this->memory);
    }

    public function add(string $key): void
    {
        $this->keys[$key] = true;
        $this->flush();
    }

    public function delete(string $key): void
    {
        unset($this->keys[$key]);
        $this->flush();
    }

    public function isExist(string $key): bool
    {
        return isset($this->keys[$key]);
    }

    private function flush()
    {
        $this->flushSharedMemory($this->memory, serialize($this->keys));
    }
}
