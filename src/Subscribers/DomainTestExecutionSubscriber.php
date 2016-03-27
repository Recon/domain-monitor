<?php

namespace Subscribers;

use Events\TestExecutionEvent;
use Events\TestStatusChangeEvent;
use Models\StatusChangeQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DomainTestExecutionSubscriber implements EventSubscriberInterface
{

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'domain.test.succeded' => [
                ['updateTestSuccededStatus', 10],
                ['refreshDomainStatus', -10],
                ['trackStatusChange', -10],
            ],
            'domain.test.failed'   => [
                ['updateTestFailedStatus', 10],
                ['refreshDomainStatus', -10],
                ['trackStatusChange', -10],
            ],
        ];
    }

    public function updateTestFailedStatus(TestExecutionEvent $event)
    {
        $event->getTest()->setStatus(false);
        $event->getTest()->save();
    }

    public function updateTestSuccededStatus(TestExecutionEvent $event)
    {
        $event->getTest()->setStatus(true);
        $event->getTest()->save();
    }

    /**
     * @todo Move refreshDomainStatus to a sepparate event: domain.test.finished
     */
    public function refreshDomainStatus(TestExecutionEvent $event)
    {
        $domain = $event->getTest()->getDomain();

        $failed = $total = 0;
        foreach ($domain->getTests() as $test) {
            $total++;

            if (!$test->getStatus()) {
                $failed++;
            }
        }

        if (($total == $failed) && $total > 0) {
            $domain->setStatus(\Models\Domain::STATUS_FAIL_ALL);
        } elseif ($failed > 0) {
            $domain->setStatus(\Models\Domain::STATUS_FAIL_PARTIAL);
        } else {
            $domain->setStatus(\Models\Domain::STATUS_OK);
        }

        $domain->save();
    }

    /**
     * Detects status changes
     *
     * @param TestExecutionEvent $event
     */
    public function trackStatusChange(TestExecutionEvent $event)
    {
        $test = $event->getTest();

        $oldState = StatusChangeQuery::create()
            ->orderByDate(Criteria::DESC)
            ->filterByTest($test)
            ->findOne();

        if ($oldState && $oldState->getNewStatus() == $test->getStatus()) {
            return;
        }

        if ($test->getStatus() == true) {
            $this->dispatcher->dispatch(TestStatusChangeEvent::NAME_TEST_BECOMES_SUCCESFUL, new TestStatusChangeEvent($test));
        } else {
            $this->dispatcher->dispatch(TestStatusChangeEvent::NAME_TEST_BECOMES_FAILED, new TestStatusChangeEvent($test));
        }
    }
}
