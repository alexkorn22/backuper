<?php
// debug
require_once '../functions.php';
require_once 'classes/Backuper.php';

$config = require_once 'config.php';

if (empty($argv)) {
    echo 'Browser ';
} else {
    echo "Console ";
};
$backuper = Backuper::Factory($config);
$backuper->run();
