<?php
ini_set('max_execution_time', 0);
date_default_timezone_set('Europe/Kiev');

//TOKEN
define('TOKEN', '1111');

//requires
require_once 'functions.php';
require_once 'classes/Backuper.php';
require_once 'classes/View.php';
// config
$config = include_once 'config.php';
//defines
define('ROOT', dirname(__FILE__));

if (empty($argv)) {
    // This is Browseeer!!!
    $view = new View();
    if (isset($_POST['token']) && $_POST['token'] = TOKEN) {
        if (isset($_POST['config'])) {
            $config = json_decode($_POST['config']);
        }
        if (empty($config)) {
            $view->render('errors',['errors' => ['Не найден файл параметров config.php']]);
            die();
        }
        $backuper = Backuper::Factory($config);
        $backuper->run();
        $view->render('main');
    } else {
        $view->render('auth',['action'=>'/backuper/run.php']);
    }

} else {

    echo "Console\n";
    $backuper = Backuper::Factory($config);
    $backuper->run();
};
