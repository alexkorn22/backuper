<?php

$config = [
    'prefixArchive' => 'backuper',
    'files' => [
        'active' => true,                // активность
        'excludedFolders' => [          //папки исключения
        ],
    ],
    'db' => [
        'active' => true,                // активность
        'host' => 'localhost',
        'user' => 'mysql',
        'password' => '1111',
        'name' => 'test',
    ],
    'sendBackupFileToTelegram' => [
        'active' => true,
        'chatId' => '0000000',
        'token' => '0000000',
        'name' => 'backuper.loc'
    ]
];

return $config;
