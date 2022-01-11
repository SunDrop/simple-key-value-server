<?php

namespace KV;

class MemTable implements MemTableInterface
{
    use SharedMemory;

    private const MEM_BLOCK_SIZE = 16 * 1024 * 1024;

    public const PROJECT_ID_DATA = 'd';

    /** @var array */
    private $data = [];

    /** @var \Shmop|false */
    private $memory;

    public function __construct()
    {
        $data = strstr(
            $this->initSharedMemory(
                $this->memory,
                self::PROJECT_ID_DATA,
                self::MEM_BLOCK_SIZE
            ), "\0", true);
        if (!$this->data = @unserialize($data)) {
            $this->data = [];
        }
    }

    function __destruct()
    {
        @shmop_close($this->memory);
    }

    public function set(string $key, string $val): void
    {
        $this->data[$key] = $val;
        $this->flush();
    }

    public function delete(string $key): void
    {
        unset($this->data[$key]);
        $this->flush();
    }

    public function get(string $key): string
    {
        return $this->data[$key];
    }

    public function isExist(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    public function reset()
    {
        $this->data = [];
        $this->flush();
    }

    private function flush()
    {
        return $this->flushSharedMemory($this->memory, serialize($this->data) . "\0");
    }
}
