<?php

namespace Subscribers;

use Events\TestStatusChangeEvent;
use Models\StatusChange;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Util\TestSessionTimeProvider;

class TestStatusChangeSubscriber implements EventSubscriberInterface
{

    /**
     * @var TestSessionTimeProvider
     */
    private $timeProvider;

    public function __construct(TestSessionTimeProvider $timeProvider)
    {
        $this->timeProvider = $timeProvider;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            TestStatusChangeEvent::NAME_TEST_BECOMES_SUCCESFUL => [
                ['saveStatusChange', 10],
            ],
            TestStatusChangeEvent::NAME_TEST_BECOMES_FAILED    => [
                ['saveStatusChange', 10],
            ],

        ];
    }

    /**
     * @param TestStatusChangeEvent $event
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function saveStatusChange(TestStatusChangeEvent $event)
    {
        $statusChangeRecord = new StatusChange();

        $statusChangeRecord
            ->setTest($event->getTest())
            ->setNewStatus($event->getTest()->getStatus())
            ->setOldStatus(!$event->getTest()->getStatus())
            ->setDate($this->timeProvider->getTime())
            ->save();
    }

}
