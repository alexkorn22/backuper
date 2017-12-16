<?php

class Backuper{

    protected $root;

    public function __construct(){
        $this->root = $_SERVER['DOCUMENT_ROOT'];
    }

    public static function Factory($config) {
        $item = new Backuper();
        return $item;
    }

    public function makeBackupFiles() {
        //$phar = new PharData('project.tar');
        //$res = $phar->buildFromDirectory($this->root . '/site');
       // debug($res);
//        $tmpDir = sys_get_temp_dir();
//        $path = './';
//        // build command
//        $outputFile = $tmpDir . DIRECTORY_SEPARATOR . 'test' . '.tar';
//        $cmd = strtr('@tar -cf @output -C ./ @file', array(
//            '@tar' => 'tar',
//            '@output' => $outputFile,
//            '@dir' => dirname($path),
//            '@file' => ''//basename($path),
//        ));
//        // exclude files
//        $option['exclude'] = 'tar';
//        if (!empty($option['exclude'])) {
//            foreach (explode(',', $option['exclude']) as $file) {
//                $cmd .= strtr(' --exclude="@file"', array(
//                    '@file' => trim($file)
//                ));
//            }
//        }
//        var_dump($cmd);
//        shell_exec($cmd);
    }

}
//https://github.com/AlexeyFreelancer/BackupTask
// example
function backupFiles($backup_folder, $backup_name, $dir)
{
    $fullFileName = $backup_folder . '/' . $backup_name . '.tar.gz';
    shell_exec("tar -cvf " . $fullFileName . " " . $dir . "/* ");
    return $fullFileName;
}

$backup_folder = './backup';    // куда будут сохранятся файлы
$backup_name = 'my_site_backup';    // имя архива
$dir = './sxd';    // что бэкапим
//backupFiles($backup_folder,$backup_name, $dir);
//shell_exec("tar -zcf ./archive_name.tar.gz ./");