<?php

namespace KV;

class OpManager implements OpManagerInterface
{
    use SharedMemory;

    public const OP_LIMIT = 100;

    public const PROJECT_ID_OP_COUNT = 'c';

    /** @var \Shmop|false */
    private $opMem;

    private static int $opCount = 0;

    public function __construct()
    {
        self::$opCount = (int) strstr(
            $this->initSharedMemory(
                $this->opMem,
                self::PROJECT_ID_OP_COUNT,
                PHP_INT_SIZE
            ),
            "\0", true
        );
    }

    public function __destruct()
    {
        @shmop_close($this->opMem);
    }

    public function inc()
    {
        ++self::$opCount;
        $this->flush();
    }

    public function isLimit(): bool
    {
        return self::$opCount >= self::OP_LIMIT;
    }

    public function reset()
    {
        self::$opCount = 0;
        $this->flush();
    }

    public static function getOpCount()
    {
        return self::$opCount;
    }

    private function flush()
    {
        return $this->flushSharedMemory($this->opMem, self::$opCount . "\0");
    }
}
