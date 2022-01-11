<?php

include '../vendor/autoload.php';

// TEST
$storage = new KV\Storage();

$key = $storage->get('key-159');
var_dump($key);
exit;

for ($i = 1010; $i < 1434; $i++) {
    $storage->set("key-$i", '='.$i);
}
