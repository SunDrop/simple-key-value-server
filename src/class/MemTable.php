<?php

namespace KV;

class MemTable implements MemTableInterface
{
    use SharedMemory;

    private const MEM_BLOCK_SIZE = 16 * 1024 * 1024;

    private const OP_LIMIT = 100;

    public const PROJECT_ID_DATA = 'd';

    public const PROJECT_ID_OP_COUNT = 'c';

    private static int $opCount = 0;

    /** @var array */
    private $data = [];

    /** @var \Shmop|false */
    private $memory;

    /** @var \Shmop|false */
    private $opMem;

    public function __construct()
    {
        $this->initData();
        $this->initOpCount();
    }

    function __destruct()
    {
        @shmop_close($this->memory);
        @shmop_close($this->opMem);
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

    public static function getOpCount()
    {
        return self::$opCount;
    }

    private function flush()
    {
        if (++self::$opCount >= self::OP_LIMIT) {
            self::$opCount = 0;
            // TODO: Dump Memtable to SSTable
            shmop_delete($this->opMem);
            $this->initOpCount();
        }
        $this->flushSharedMemory($this->opMem, self::$opCount);

        return $this->flushSharedMemory($this->memory, serialize($this->data));
    }

    private function initData()
    {
        $data = $this->initSharedMemory(
            $this->memory,
            self::PROJECT_ID_DATA,
            self::MEM_BLOCK_SIZE
        );
        if (!$this->data = @unserialize($data)) {
            $this->data = [];
        }
    }

    private function initOpCount()
    {
        self::$opCount = (int) $this->initSharedMemory(
            $this->opMem,
            self::PROJECT_ID_OP_COUNT,
            PHP_INT_SIZE
        );
    }
}
