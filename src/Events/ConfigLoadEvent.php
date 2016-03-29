<?php

namespace Events;

use Symfony\Component\EventDispatcher\Event;
use Util\Config\ConfigLoader;

class ConfigLoadEvent extends Event
{

    const NAME = 'config.loaded';
    /**
     * @var ConfigLoader
     */
    private $configLoader;


    /**
     * ConfigLoadEvent constructor.
     *
     * @param ConfigLoader $configLoader
     */
    public function __construct(ConfigLoader $configLoader)
    {
        $this->configLoader = $configLoader;
    }

    /**
     * @return ConfigLoader
     */
    public function getConfigLoader()
    {
        return $this->configLoader;
    }


}
