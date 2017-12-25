<?php
// debug
require_once '../functions.php';
require_once 'classes/Backuper.php';

$config = require_once 'config.php';

$backuper = Backuper::Factory($config);
$backuper->makeBackupFiles();