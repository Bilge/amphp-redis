<?php

require __DIR__ . '/../vendor/autoload.php';

use function Amp\async;
use function Amp\await;

$config = Amp\Redis\Config::fromUri('tcp://localhost:6379');

$promise = async(function () use ($config): void {
    $popClient = new Amp\Redis\Redis(new Amp\Redis\RemoteExecutor($config));
    try {
        $value = $popClient->getList('foobar-list')->popHeadBlocking();
        print 'Value: ' . \var_export($value, true) . PHP_EOL;
    } catch (\Throwable $error) {
        print 'Error: ' . $error->getMessage() . PHP_EOL;
    }
});

$pushClient = new Amp\Redis\Redis(new Amp\Redis\RemoteExecutor($config));

print 'Pushing value…' . PHP_EOL;
$pushClient->getList('foobar-list')->pushHead('42');
print 'Value pushed.' . PHP_EOL;

await($promise);
