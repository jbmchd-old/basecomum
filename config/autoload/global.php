<?php

return array(
    'app-config-global' => [
        'session' => [
            'owner-config' => [
                'nome' => 'nucleo',
            ],
            'zend-config' => [
                'name'=>'nucleo',
                'remember_me_seconds' => 60, //limite segundos
                'use_cookies' => true,
                'cookie_httponly' => true,
            ]
        ],
    ],
    'module_layouts' => [
        'Application' => 'layout/padrao',
        'Acesso' => 'layout/padrao',
        'Testes' => 'layout/padrao',
    ],
    'zendexperts_zedb' => array(
        'adapter' => array(
            'driver' => 'Pdo_Mysql',
            'driver_options' => [
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
            ],
        ),
    ),
);
