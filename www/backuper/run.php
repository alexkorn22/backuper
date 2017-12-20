<?php
// debug
require_once '../functions.php';
// подключение классов
require_once 'classes/Backuper.php';
require_once 'classes/Tar.php';

$config = require_once 'config.php';

//$backuper = Backuper::Factory($config);
//$backuper->makeBackupFiles();
$output = shell_exec('ls');
echo "<pre>$output</pre>";
//shell_exec("tar -zcf ./archive_name.tar.gz ./");
var_dump(class_exists('PEAR'));
$tar = new Archive_Tar('test.tar');
$tar->_debug = true;
$tar->add(['../site']);
var_dump($tar);