<?php

class Backuper{

    protected $root;
    protected $nameFile = '';
    protected $nameDb = '';
    protected $strCmdFiles = '';
    protected $folderInput = 'backuper/backup';
    protected $excludes = [];

    protected function __construct(){
        $this->root = $_SERVER['DOCUMENT_ROOT'];
    }

    public static function Factory($options) {
        $item = new Backuper();
        $item->makeNameFile();
        $item->excludes[] = $item->folderInput;
        mkdir($_SERVER['DOCUMENT_ROOT'] . '/' . $item->folderInput);

        return $item;
    }

    public function makeBackupFiles() {
        // build command
        $ext =  'tar';
        $out = $this->folderInput . '/' . $this->nameFile . '.' . $ext;
        $this->strCmdFiles = strtr('@tar -cf @output  @dir @file', array(
            '@tar' => 'tar',
            '@output' => $out,
            '@dir' => '../',
            '@file' => '',
        ));
        // exclude files
        $this->addExludes();
        var_dump($this->strCmdFiles);
        shell_exec($this->strCmdFiles);
    }

    protected function addExludes() {
        foreach ($this->excludes as $file) {
            $this->strCmdFiles .= strtr(' --exclude="@file"', array(
                '@file' => trim($file)
            ));
        }
    }

    protected function makeNameFile() {
        if (isset($_SERVER['HTTP_HOST'])) {
            $this->nameFile = $_SERVER['HTTP_HOST'];
        }
        $this->nameFile .= '_' . date('Ymd_his');
    }

}
