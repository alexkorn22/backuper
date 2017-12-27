<?php

class Backuper{

    /**
     * @var Archive_Tar
     */
    protected $tar;
    protected $root;
    protected $doFiles = true;
    protected $doDb = true;
    protected $nameFile = '';
    protected $extFileTar = '.tar';
    protected $outputFolder = 'backups';
    protected $rootFolderName = 'backuper';
    protected $excluded = [];
    protected $nameDb = '';
    protected $dbHost = 'localhost';
    protected $dbUser = 'mysql';
    protected $dbPass = '1111';
    protected $dbName = 'test';

    protected function __construct(){
        $this->root = dirname(__DIR__);
        $this->root = dirname($this->root);
        $this->excluded = ['..' . DIRECTORY_SEPARATOR . 'backuper'];
    }

    public static function Factory($options) {
        // подключение вспомогательных классов
        require_once 'Tar.php';

        $item = new Backuper();
        $item->makeOutputFolder();
        $item->setOptions($options);
        $item->makeNameFile();

        $item->tar = new Archive_Tar($item->outputFolder . DIRECTORY_SEPARATOR . $item->nameFile . $item->extFileTar);
        $item->tar->_debug = true;

        // for debug
        $nameFile = $item->root . DIRECTORY_SEPARATOR . $item->rootFolderName .
            DIRECTORY_SEPARATOR. $item->outputFolder .
            DIRECTORY_SEPARATOR. $item->nameFile . $item->extFileTar;
        if (file_exists($nameFile)) {
            unlink($nameFile);
        }

        return $item;
    }

    public function makeBackupFiles() {
        $this->tar->setIgnoreList($this->getListIgnoreFiles());
        $this->addFilesToTar();
    }

    public function makeBackupDb() {
        $fullFileName = '..' . DIRECTORY_SEPARATOR . $this->nameFile . '.sql';
        $command = 'mysqldump -h ' . $this->dbHost . ' -u ' . $this->dbUser . ' -p' . $this->dbPass . ' ' . $this->dbName. ' > ' . $fullFileName;
       shell_exec($command);
      // $this->addFileToTar($this->nameFile . '.sql');
    }

    public function run() {
        if ($this->doDb) {
            $this->makeBackupDb();
        }
        if ($this->doFiles) {
            $this->makeBackupFiles();
        }
        $this->deleteDbFile();
    }

    protected function deleteDbFile() {
        $nameFileDb = $this->root . DIRECTORY_SEPARATOR . $this->nameFile . '.sql';
        if (file_exists($nameFileDb)) {
            unlink($nameFileDb);
        }
    }

    protected function addFilesToTar() {
        foreach (glob($this->root . DIRECTORY_SEPARATOR . '*') as $file) {
            $nameFile = basename($file);
            if ($nameFile == $this->rootFolderName) {
                continue;
            }
            $this->addFileToTar($nameFile);
        }
    }

    protected function addFileToTar($path) {
        $this->tar->add(['..' . DIRECTORY_SEPARATOR . $path]);
    }

    protected function getListIgnoreFiles() {
        $res = [];
        foreach ($this->excluded as $file) {
            $res[] = '..' . DIRECTORY_SEPARATOR . $file;
        }
        return $res;
    }

    protected function makeNameFile() {
        $this->nameFile .= '_' . date('Ymd');
    }

    protected function makeOutputFolder() {
        $folder = $this->root . DIRECTORY_SEPARATOR. $this->rootFolderName . DIRECTORY_SEPARATOR . $this->outputFolder;
        if (!file_exists($folder)) {
            mkdir($folder);
        }
    }

    protected function setOptions($options) {
        if (!$options['files']['active']) {
            $this->doFiles = false;
        }
        if (!$options['db']['active']) {
            $this->doDb = false;
        }
        foreach ($options['files']['excludedFolders'] as $excludedFolder) {
            $this->excluded[] = $excludedFolder;
        }

        $this->nameFile = $options['prefixArchive'];

        $this->dbHost = $options['db']['host'];
        $this->dbName = $options['db']['name'];
        $this->dbUser = $options['db']['user'];
        $this->dbPass = $options['db']['password'];

    }

}
