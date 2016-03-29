<?php
$serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
$serviceContainer->checkVersion('2.0.0-dev');
$serviceContainer->setAdapterClass('default', 'mysql');
$manager = new \Propel\Runtime\Connection\ConnectionManagerSingle();
$manager->setConfiguration([
    'dsn'       => 'mysql:host=192.168.1.31;port=3306;dbname=work_website_monitor',
    'user'      => 'work',
    'password'  => 'xtasia',
    'settings'  =>
        [
            'charset' => 'utf8',
            'queries' =>
                [
                ],
        ],
    'classname' => '\\Propel\\Runtime\\Connection\\ConnectionWrapper',
]);
$manager->setName('default');
$serviceContainer->setConnectionManager('default', $manager);
$serviceContainer->setDefaultDatasource('default');
