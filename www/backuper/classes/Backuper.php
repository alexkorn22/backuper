<?php

class Backuper{

    public function __construct(){
    }

    public static function Factory($config) {
        $item = new Backuper();
        return $item;
    }

    public function makeBackupFiles() {

    }

}
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