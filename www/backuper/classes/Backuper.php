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
       $tmpDir = sys_get_temp_dir();
           $path = '../';
        // build command
        $outputFile =  'backups/test' . '.tar';
        $cmd = strtr('@tar -cf @output  @dir @file', array(
            '@tar' => 'tar',
            '@output' => $outputFile,
            '@dir' => $path,
            '@file' => ''//basename($path),
        ));
        // exclude files
        $option['exclude'] = 'backuper/backups';
        if (!empty($option['exclude'])) {
            foreach (explode(',', $option['exclude']) as $file) {
                $cmd .= strtr(' --exclude="@file"', array(
                    '@file' => trim($file)
                ));
            }
        }

        var_dump($cmd);
        shell_exec($cmd);
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