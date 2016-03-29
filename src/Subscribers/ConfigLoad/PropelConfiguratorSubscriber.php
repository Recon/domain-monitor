<?php

namespace Subscribers\ConfigLoad;

use Events\ConfigLoadEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PropelConfiguratorSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            ConfigLoadEvent::NAME => [
                ['load', 10],
            ],
        ];
    }

    /**
     * Creates a Propel Connection
     *
     * @param ConfigLoadEvent $event
     */
    public function load(ConfigLoadEvent $event)
    {
        $config = $event->getConfigLoader();

        $serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
        $serviceContainer->checkVersion('2.0.0-dev');
        $serviceContainer->setAdapterClass('default', 'mysql');
        $manager = new \Propel\Runtime\Connection\ConnectionManagerSingle();
        $manager->setConfiguration([
            'dsn'       => sprintf('mysql:host=%s;port=%s;dbname=%s', $config->get('db_host'), $config->get('db_port'),
                $config->get('db_name')),
            'user'      => $config->get('db_user'),
            'password'  => $config->get('db_pass'),
            'settings'  =>
                [
                    'charset' => 'utf8',
                    'queries' => [],
                ],
            'classname' => '\\Propel\\Runtime\\Connection\\ConnectionWrapper',
        ]);
        $manager->setName('default');
        $serviceContainer->setConnectionManager('default', $manager);
        $serviceContainer->setDefaultDatasource('default');
    }
}
