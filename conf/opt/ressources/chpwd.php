<?php

use Kanboard\Core\Controller\Runner;
use Kanboard\Console\ResetPasswordCommand;

try {
    require __DIR__.'/app/common.php';
    $container['router']->dispatch();

    $RPC = new ResetPasswordCommand($container);
    if ($argc != 2) {
        die('ERROR : No password given' . PHP_EOL);
    }

    $password = $argv[1];

    $userId = $RPC->userModel->getIdByUsername('admin');
    if (empty($userId)) {
        die('ERROR : User admin not found' . PHP_EOL);
    }

    if (!$RPC->userModel->update(array('id' => $userId, 'password' => $password))) {
        die('ERROR : Couldn\'t update password' . PHP_EOL);
    }
    echo('SUCCESS' . PHP_EOL);
    return true;
} catch (Exception $e) {
    echo 'Internal Error: '.$e->getMessage() . PHP_EOL;
}