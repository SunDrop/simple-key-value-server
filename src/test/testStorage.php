<?php

use KV\NotExistKeyException;

include '../vendor/autoload.php';

// TEST
$storage = new KV\Storage();

echo '<pre>';
echo 'GET key: "key-1059"' . PHP_EOL;
echo '$storage->get("key-1059")' . PHP_EOL;
try {
    $val = $storage->get("key-1059");
    echo "Key: key-1059, value: $val \n";
} catch (NotExistKeyException $e) {
    echo $e->getMessage() . PHP_EOL;
}

echo PHP_EOL;
echo 'Set keys in cycle from key-1010 to key-1134' . PHP_EOL;
echo '$storage->set("key-$i", $i)' . PHP_EOL;
for ($i = 1010; $i < 1134; $i++) {
    $storage->set("key-$i", $i);
}
echo 'SSTable dump should be presented on the disk' . PHP_EOL;
