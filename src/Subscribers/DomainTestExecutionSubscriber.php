<?php

namespace Subscribers;

use \Events\TestExecutionEvent;
use \Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DomainTestExecutionSubscriber implements EventSubscriberInterface
{

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'domain.test.succeded' => [
                ['updateTestSuccededStatus', 10],
                ['refreshDomainStatus', -10],
            ],
            'domain.test.failed' => [
                ['updateTestFailedStatus', 10],
                ['refreshDomainStatus', -10],
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

}
