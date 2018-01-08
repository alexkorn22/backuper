<?php

class Backuper{

    /**
     * @var Archive_Tar
     */
    protected $tar;
    protected $ds = '/';
    protected $root;
    protected $doFiles = true;
    protected $doDb = true;
    protected $nameFile = '';
    protected $nameFileWithPath = '';
    protected $extFileTar = '.tar';
    protected $outputFolder = 'backups';
    protected $rootFolderName = 'backuper';
    protected $excluded = [];
    protected $tgChatIdForSendBackupFile = false;
    protected $tgNameForSendBackupFile = '';
    protected $tgToken = '';
    protected $nameDb = '';
    protected $dbHost = 'localhost';
    protected $dbUser = 'mysql';
    protected $dbPass = '1111';
    protected $dbName = 'test';

    protected function __construct(){
        $this->root = dirname(__DIR__);
        $this->root = dirname($this->root);
    }

    public static function Factory($options) {
        // подключение вспомогательных классов
        self::requireLibs();

        $item = new Backuper();
        $item->makeOutputFolder();
        $item->setOptions($options);
        $item->makeNameFile();

        $item->nameFileWithPath = $item->outputFolder . $item->ds . $item->nameFile . $item->extFileTar;

        $item->tar = new Archive_Tar($item->nameFileWithPath);
        //$item->tar->_debug = true;

        // for debug
        $item->deleteOldFile();

        return $item;
    }

    protected function deleteOldFile() {
        $nameFile = $this->root . $this->ds . $this->rootFolderName .
            $this->ds. $this->outputFolder .
            $this->ds. $this->nameFile . $this->extFileTar;
        if (file_exists($nameFile)) {
            unlink($nameFile);
        }
    }

    protected static function requireLibs() {
        require_once 'Tar.php';
        require_once 'Telegram.php';
    }

    public function makeBackupFiles() {
        $this->tar->setIgnoreList($this->getListIgnoreFiles());
        $this->addFilesToTar();
    }

    public function makeBackupDb() {
        $fullFileName = '..' . $this->ds . $this->nameFile . '.sql';
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
        if ($this->tgChatIdForSendBackupFile) {
            $this->sendFilesToTelegram();
        }
        $this->deleteDbFile();
    }

    protected function deleteDbFile() {
        $nameFileDb = $this->root . $this->ds . $this->nameFile . '.sql';
        if (file_exists($nameFileDb)) {
            unlink($nameFileDb);
        }
    }

    protected function addFilesToTar() {
        foreach (glob($this->root . $this->ds . '*') as $file) {
            $nameFile = basename($file);
            if ($nameFile == $this->rootFolderName) {
                continue;
            }
            $this->addFileToTar($nameFile);
        }
    }

    protected function addFileToTar($path) {
        $this->tar->add(['..' . $this->ds . $path]);
    }

    protected function getListIgnoreFiles() {
        $res = [];
        foreach ($this->excluded as $file) {
            $res[] = '..' . $this->ds . $file;
        }
        return $res;
    }

    protected function makeNameFile() {
        $this->nameFile .= '_' . date('Ymd_His');
    }

    protected function makeOutputFolder() {
        $folder = $this->root . $this->ds . $this->rootFolderName . $this->ds . $this->outputFolder;
        if (!file_exists($folder)) {
            mkdir($folder);
        }
    }

    protected function sendFilesToTelegram() {
        $fullNameFile = $this->root . $this->ds. $this->rootFolderName . $this->ds . $this->nameFileWithPath;
        if (is_file($fullNameFile)) {
            $tg = new Telegram($this->tgToken,$this->tgChatIdForSendBackupFile);
            $msg = $this->tgNameForSendBackupFile;
            $msg .= ' ' . date('Y m d');
            $tg->sendMessage($msg);
            $tg->sendFile($fullNameFile);
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

        if ($options['sendBackupFileToTelegram']['active']) {
            $this->tgChatIdForSendBackupFile = $options['sendBackupFileToTelegram']['chatId'];
            $this->tgNameForSendBackupFile = $options['sendBackupFileToTelegram']['name'];
            $this->tgToken = $options['sendBackupFileToTelegram']['token'];
        }

    }

}
