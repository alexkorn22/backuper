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
    ]
];

return $config;
