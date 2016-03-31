<?php

namespace Subscribers\ConfigLoad;

use Events\ConfigLoadEvent;
use Propel\Runtime\Connection\ConnectionManagerSingle;
use Propel\Runtime\Propel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Util\PropelConnectionConfigFactory;

class PropelConfiguratorSubscriber implements EventSubscriberInterface
{

    /**
     * @var PropelConnectionConfigFactory
     */
    private $propelConnectionConfig;

    public function __construct(PropelConnectionConfigFactory $propelConnectionConfig)
    {
        $this->propelConnectionConfig = $propelConnectionConfig;
    }

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
        $serviceContainer = Propel::getServiceContainer();
        $serviceContainer->checkVersion('2.0.0-dev');
        $serviceContainer->setAdapterClass('default', 'mysql');
        $manager = new ConnectionManagerSingle();
        $manager->setConfiguration($this->propelConnectionConfig->create());
        $manager->setName('default');
        $serviceContainer->setConnectionManager('default', $manager);
        $serviceContainer->setDefaultDatasource('default');
    }

}
