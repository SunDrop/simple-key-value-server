<?php

include '../vendor/autoload.php';

// TEST
$removedKeysTable = new KV\RemovedKeysTable();

echo '<pre>';
echo 'Check exists key: "rem"' . PHP_EOL;
echo '$removedKeysTable->isExist("rem")' . PHP_EOL;
$q1 = $removedKeysTable->isExist("rem");
echo 'Result: ';
var_dump($q1);
echo PHP_EOL;

echo 'Add key: "newrem"' . PHP_EOL;
echo '$removedKeysTable->add("newrem")' . PHP_EOL;
$removedKeysTable->add("newrem");
echo 'Check exists key: "newrem"' . PHP_EOL;
echo '$removedKeysTable->isExist("newrem")' . PHP_EOL;
$q2 = $removedKeysTable->isExist("newrem");
echo 'Result: ';
var_dump($q2);
echo PHP_EOL;

echo 'Delete key: "newrem"' . PHP_EOL;
echo '$removedKeysTable->delete("newrem")' . PHP_EOL;
$removedKeysTable->delete("newrem");
echo 'Check exists key: "newrem"' . PHP_EOL;
echo '$removedKeysTable->isExist("newrem")' . PHP_EOL;
$q3 = $removedKeysTable->isExist("newrem");
echo 'Result: ';
var_dump($q3);
echo PHP_EOL;
