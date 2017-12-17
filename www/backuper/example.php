<?php
require_once '../functions.php';
// register autoloader
define('ROOT', dirname(__DIR__));
spl_autoload_register(function ($class) {
    $arPath = explode('\\',$class);
    if ($arPath[0] == 'BackupTask') {
        $arPath[0] = 'backuper';
    }
    $file = implode(DIRECTORY_SEPARATOR,$arPath);
    $file = ROOT . DIRECTORY_SEPARATOR . $file . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

$config = include 'config.php';
$backupTask = new BackupTask\BackupTask($config);
$backupTask->run();