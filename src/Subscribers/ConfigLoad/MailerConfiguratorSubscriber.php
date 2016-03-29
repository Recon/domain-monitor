<?php

namespace Subscribers\ConfigLoad;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MailerConfiguratorSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [

        ];
    }

}
