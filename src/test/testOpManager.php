<?php

include '../vendor/autoload.php';

// TEST
$opManager = new KV\OpManager();

echo '<pre>';
echo 'Get count' . PHP_EOL;
echo 'KV\OpManager::getOpCount() or $opManager::getOpCount()' . PHP_EOL;
echo 'Count: ' . $opManager::getOpCount() . PHP_EOL;

echo PHP_EOL;
echo 'Increment' . PHP_EOL;
echo '$opManager->inc()' . PHP_EOL;
$opManager->inc();
echo 'Count: ' . $opManager::getOpCount() . PHP_EOL;

echo PHP_EOL;
echo 'Is Limit (current limit is ' . $opManager::OP_LIMIT . ')' . PHP_EOL;
echo '$opManager->isLimit()' . PHP_EOL;
echo 'Result: ';
var_dump($opManager->isLimit());

echo PHP_EOL;
echo 'Making +50 inc() ...' . PHP_EOL;
for ($i = 0; $i < 50; ++$i) {
    $opManager->inc();
}

echo PHP_EOL;
echo 'Is Limit' . PHP_EOL;
echo '$opManager->isLimit()' . PHP_EOL;
echo 'Result: ';
var_dump($opManager->isLimit());

echo PHP_EOL;
echo 'If Limit >= 500 call reset()' . PHP_EOL;
echo '$opManager->reset()' . PHP_EOL;
if ($opManager::getOpCount() >= 500) {
    $opManager->reset();
}
