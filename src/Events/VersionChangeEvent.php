<?php

namespace Events;

use Symfony\Component\EventDispatcher\Event;

class VersionChangeEvent extends Event
{

    const NAME = 'app.version_change';

    protected $messages = [];

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param string $message
     */
    public function addMessage($message)
    {
        $this->messages[] = $message;
    }


}
