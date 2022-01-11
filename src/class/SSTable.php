<?php

namespace KV;

class SSTable implements SSTableInterface
{
    public const FILES_PATH = __DIR__ . '/../sstable-data/';

    private array $files = [];

    private string $currentDumpFile = '';

    public function __construct()
    {
        $this->initFiles();
    }

    public function get(string $key): string
    {
        return $this->binarySearchKey($key);
    }

    public function isExist(string $key): bool
    {
        return false === $this->binarySearchKey($key);
    }

    public function dump(array $sortedData, array $removedKeys): void
    {
        $this->currentDumpFile = self::FILES_PATH . date('YmdHis') . '.sst';
        $this->initFiles();
        if ($this->files) {
            $this->sortedMerge($sortedData, $removedKeys);
        } else {
            foreach ($sortedData as $key => $val) {
                $this->dumpItem($key, $val);
            }
        }
        $this->initFiles();
    }

    private function initFiles()
    {
        $this->files = glob(self::FILES_PATH . '*.sst');
        if (($fileKey = array_search($this->currentDumpFile, $this->files)) !== false) {
            unset($this->files[$fileKey]);
        }
        if (empty($this->files)) {
            $this->files = [];
        }
    }

    private function sortedMerge(array $sortedData, array $removedKeys): void
    {
        $oldSSTableFileName = current($this->files);
        $memVal = current($sortedData);
        $memKey = key($sortedData);
        $handle = fopen($oldSSTableFileName, 'r');
        $fileIsDone = $memIsDone = false;

        while (!$memIsDone || !$fileIsDone) {
            if (!$fileIsDone) {
                if (($line = fgets($handle)) === false) {
                    $fileIsDone = true;
                }
                [$ssKey, $ssVal] = $this->readLine($line);
                if ($memIsDone) {
                    // If $key exists in removedKeysTable we should "remove" it from SSTable
                    // so, just skip this key
                    if (array_key_exists($ssKey, $removedKeys)) {
                        continue;
                    }
                    $this->dumpItem($ssKey, $ssVal);
                } elseif ($ssKey < $memKey) {
                    if (array_key_exists($ssKey, $removedKeys)) {
                        continue;
                    }
                    $this->dumpItem($ssKey, $ssVal);
                } else {
                    // MemTable has more priority than SSTable
                    $this->dumpItem($memKey, $memVal);
                    $memVal = next($sortedData);
                    $memKey = key($sortedData);
                    if (false === $memVal) {
                        $memIsDone = true;
                    }
                }
            } elseif (!$memIsDone) {
                $this->dumpItem($memKey, $memVal);
                $memVal = next($sortedData);
                $memKey = key($sortedData);
                if (false === $memVal) {
                    $memIsDone = true;
                }
            }
        }

        if (($fileKey = array_search($oldSSTableFileName, $this->files)) !== false) {
            unset($this->files[$fileKey]);
        }
        fclose($handle);
        unlink($oldSSTableFileName);
    }

    private function dumpItem(string $key, string $val): void
    {
        file_put_contents(
            $this->currentDumpFile,
            $key . "\0" . serialize($val) . PHP_EOL,
            FILE_APPEND
        );
    }

    private function binarySearchKey(string $key)
    {
        $file = new \SplFileObject(current($this->files), 'r');
        $file->seek($file->getSize());
        $low = 0;
        $high = $file->key();

        while ($low <= $high) {
            $mid = floor(($low + $high) / 2);

            $file->seek($mid);
            $line = $file->current();
            [$ssKey, $ssVal] = $this->readLine($line);

            if ($ssKey === $key) {
                return $ssVal;
            }

            if ($key < $ssKey) {
                $high = $mid - 1;
            } else {
                $low = $mid + 1;
            }
        }

        return false;
    }

    private function readLine($line)
    {
        $ssKey = strstr($line, "\0", true);
        $ssVal = @unserialize(ltrim(strstr($line, "\0"), "\0"));

        return [$ssKey, $ssVal];
    }
}
