<?php

class Backuper{

    /**
     * @var Archive_Tar
     */
    protected $tar;
    protected $root;
    protected $doFiles = true;
    protected $doSql = true;
    protected $nameFile = '';
    protected $extFileTar = '.tar';
    protected $outputFolder = 'backups';
    protected $rootFolderName = 'backuper';
    protected $excluded = ['../backuper'];
    protected $nameDb = '';

    protected function __construct(){
        $this->root = $_SERVER['DOCUMENT_ROOT'];
    }

    public static function Factory($options) {
        // подключение вспомогательных классов
        require_once 'Tar.php';

        $item = new Backuper();
        $item->root = $_SERVER['DOCUMENT_ROOT'];
        $item->makeNameFile();
        $item->makeOutputFolder();

        $item->setOptions($options);

        $item->tar = new Archive_Tar($item->outputFolder . '/'. $item->nameFile . $item->extFileTar);
        $item->tar->_debug = true;

        //$item->excludes[] = $item->folderInput;
        //mkdir($_SERVER['DOCUMENT_ROOT'] . '/' . $item->folderInput);

        // for debug
       unlink($item->root . '/' . $item->rootFolderName .'/' . $item->outputFolder . '/' . $item->nameFile . $item->extFileTar);

        return $item;
    }

    public function makeBackupFiles() {
        $this->tar->setIgnoreList($this->getListIgnoreFiles());
        $this->addFilesToTar();
        debug($this->tar);
    }

    protected function addFilesToTar() {
        foreach (glob($this->root . '/*') as $file) {
            $nameFile = basename($file);
            if ($nameFile == $this->rootFolderName) {
                continue;
            }
            $this->addFileToTar($nameFile);
        }
    }

    protected function addFileToTar($path) {
        $this->tar->add(['../' . $path]);
    }

    protected function getListIgnoreFiles() {
        $res = [];
        foreach ($this->excluded as $file) {
            $res[] = '../' . $file;
        }
        return $res;
    }

    protected function makeNameFile() {
        if (isset($_SERVER['HTTP_HOST'])) {
            $this->nameFile = $_SERVER['HTTP_HOST'];
        }
        //$this->nameFile = 'test';
        $this->nameFile .= '_' . date('Ymd');
    }

    protected function makeOutputFolder() {
        $folder = $this->root . '/'. $this->rootFolderName . '/' . $this->outputFolder;
        if (!file_exists($folder)) {
            mkdir($folder);
        }
    }

    protected function setOptions($options) {
        if (!$options['files']['active']) {
            $this->doFiles = false;
        }
        if (!$options['db']['active']) {
            $this->doSql = false;
        }
        foreach ($options['files']['excludedFolders'] as $excludedFolder) {
            $this->excluded[] = $excludedFolder;
        }
    }

}
