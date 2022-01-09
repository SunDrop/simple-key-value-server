<?php

namespace KV;

class MemTable
{
    private const MEM_BLOCK_SIZE = 16 * 1024 * 1024;
    private const OP_LIMIT = 100;

    private static int $opCount = 0;

    /** @var array */
    private $data = [];

    /** @var Shmop|false */
    private $memory;
    /** @var Shmop|false */
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

    public function set($key, $val)
    {
        $this->data[$key] = $val;
        $this->flush();
    }

    public function delete($key)
    {
        unset($this->data[$key]);
        $this->flush();
    }

    public function get($key)
    {
        return $this->data[$key];
    }

    public function isExist($key)
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
        shmop_write($this->opMem, self::$opCount, 0);
        return shmop_write($this->memory, serialize($this->data), 0);
    }

    private function initData()
    {
        $key = ftok(__FILE__, 'd');
        $this->memory = shmop_open($key, "c", 0600, self::MEM_BLOCK_SIZE);
        $data = shmop_read($this->memory, 0, shmop_size($this->memory));
        if (!$this->data = @unserialize($data)) {
            $this->data = [];
        }
    }

    private function initOpCount()
    {
        $key = ftok(__FILE__, 'c');
        $this->opMem = shmop_open($key, "c", 0600, PHP_INT_SIZE);
        self::$opCount = (int)shmop_read($this->opMem, 0, PHP_INT_SIZE);
    }
}
