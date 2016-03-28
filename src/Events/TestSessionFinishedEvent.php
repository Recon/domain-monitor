<?php

namespace Events;

use Symfony\Component\EventDispatcher\Event;

class TestSessionFinishedEvent extends Event
{
    const NAME = 'domain.test_session.finished';
}
