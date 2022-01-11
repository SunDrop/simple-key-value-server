<?php

include '../vendor/autoload.php';

// TEST
$memtable = new KV\MemTable();

echo '<pre>';
echo 'Set key: "as", value: "asaas"' . PHP_EOL;
echo '$memtable->set("as", "asaas")' . PHP_EOL;
$memtable->set('as', 'asaas');
echo 'Get key: "as"' . PHP_EOL;
echo '$memtable->get("as")' . PHP_EOL;
echo 'Result: ', $memtable->get('as') . PHP_EOL . PHP_EOL;

echo 'Set key: "zz", value: "zzazz"' . PHP_EOL;
echo '$memtable->set("zz", "zzazz")' . PHP_EOL;
$memtable->set('zz', 'zzazz');
echo 'Get key: "zz"' . PHP_EOL;
echo '$memtable->get("zz")' . PHP_EOL;
$zz = $memtable->get('zz');
echo 'Result: ', $memtable->get('zz') . PHP_EOL . PHP_EOL;

echo 'Check exists key: "zz"' . PHP_EOL;
echo '$memtable->isExist("zz")' . PHP_EOL;
$q1 = $memtable->isExist('zz');
echo 'Result: ';
var_dump($q1);
echo 'Delete key: "zz"' . PHP_EOL;
echo '$memtable->delete("zz")' . PHP_EOL;
$memtable->delete('zz');
echo 'Check exists key: "zz"' . PHP_EOL;
echo '$memtable->isExist("zz")' . PHP_EOL;
$q2 = $memtable->isExist('zz');
echo 'Result: ';
var_dump($q2);
echo PHP_EOL;

echo 'Check non exists key: "as1"' . PHP_EOL;
echo '$memtable->isExist("as1")' . PHP_EOL;
$as1 = $memtable->isExist('as1');
echo 'Result: ';
var_dump($as1);
echo PHP_EOL;
