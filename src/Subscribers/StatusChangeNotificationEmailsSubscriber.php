<?php

namespace Subscribers;

use Events\TestSessionFinishedEvent;
use Models\StatusChangeQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\Collection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Util\MailerFactory;
use Util\TestSessionTimeProvider;
use Util\UserTestNotifications\MailRenderer;
use Util\UserTestNotifications\UserInventoryPoolManager;

class StatusChangeNotificationEmailsSubscriber implements EventSubscriberInterface
{

    /**
     * @var TestSessionTimeProvider
     */
    private $timeProvider;

    /**
     * @var MailRenderer
     */
    private $mailRenderer;

    /**
     * @var MailerFactory
     */
    private $mailerFactory;


    /**
     * TestSessionFinishedSubscriber constructor.
     *
     * @param TestSessionTimeProvider $timeProvider
     * @param MailerFactory           $mailerFactory
     * @param MailRenderer            $mailRenderer
     * @internal param Mailer $mailer
     */
    public function __construct(
        TestSessionTimeProvider $timeProvider,
        MailerFactory $mailerFactory,
        MailRenderer $mailRenderer
    ) {
        $this->timeProvider = $timeProvider;
        $this->mailRenderer = $mailRenderer;
        $this->mailerFactory = $mailerFactory;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            TestSessionFinishedEvent::NAME => [
                ['sendNotificationEmails', 10],
            ],
        ];
    }

    public function sendNotificationEmails()
    {
        // Fetching all the changes, including all the users
        $changes = StatusChangeQuery::create()
            ->join('StatusChange.Test', Criteria::INNER_JOIN)
            ->join('StatusChange.Test.Domain', Criteria::INNER_JOIN)
            ->join('StatusChange.Test.Domain.Users', Criteria::LEFT_JOIN)
            ->filterByDate($this->timeProvider->getTime())
            ->find();

        $pool = $this->buildInventoryPool($changes);
        $this->sendPoolEmails($pool);
    }

    /**
     * @param Collection $changes Collection of StatusChange objects
     * @return UserInventoryPoolManager
     */
    protected function buildInventoryPool(Collection $changes)
    {
        $pool = new UserInventoryPoolManager();

        // Building a list of tests (which we will list in a single email) for each user
        foreach ($changes as $change) {
            foreach ($change->getTest()->getDomain()->getUsers() as $user) {
                $inventory = $pool->getUserInventory($user);
                $change->getNewStatus() ? $inventory->addSucceeded($change->getTest()) : $inventory->addFailed($change->getTest());
            }
        }

        return $pool;
    }

    /**
     * @param UserInventoryPoolManager $pool
     */
    protected function sendPoolEmails(UserInventoryPoolManager $pool)
    {
        foreach ($pool As $inventory) {
            $message = $this->mailRenderer->render($inventory);
            $subject = $this->mailRenderer->getSubject($inventory);
            $email = $inventory->getUser()->getEmail();

            $mailer = $this->mailerFactory->factory();
            $mailer->getMessage()
                ->setBody($message)
                ->setSubject($subject)
                ->setTo($email)
                ->setContentType('text/html');
            $mailer->send();
        }
    }

}
