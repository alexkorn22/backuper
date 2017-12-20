<?php
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

$config = [

];

return $config;
