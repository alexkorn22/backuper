<?php

class Backuper{

    /**
     * @var Archive_Tar
     */
    protected $tar;
    protected $root;
    protected $nameFile = '';
    protected $extFileTar = '.tar';
    protected $outputFolder = 'backups';
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

        $item->tar = new Archive_Tar($item->outputFolder . '/'. $item->nameFile . $item->extFileTar);
        $item->tar->_debug = true;

        //$item->excludes[] = $item->folderInput;
        //mkdir($_SERVER['DOCUMENT_ROOT'] . '/' . $item->folderInput);

        // for debug
       unlink($item->root . '/backuper/' . $item->outputFolder . '/' . $item->nameFile . $item->extFileTar);

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
            if ($nameFile == 'backuper') {
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

}
