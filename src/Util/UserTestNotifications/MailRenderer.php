<?php


namespace Util\UserTestNotifications;

use Symfony\Component\Templating\EngineInterface;

class MailRenderer
{
    const TEMPLATE = 'email/test_change';

    /**
     * @var UserInventory
     */
    private $inventory;

    /**
     * @var EngineInterface
     */
    private $templatingEngine;

    /**
     * MailRenderer constructor.
     *
     * @param EngineInterface $templatingEngine
     */
    public function __construct(EngineInterface $templatingEngine)
    {
        $this->templatingEngine = $templatingEngine;
    }

    public function render(UserInventory $inventory)
    {
        return $this->templatingEngine->render(static::TEMPLATE, [
            'inventory' => $inventory,
        ]);
    }

    public function getSubject(UserInventory $inventory)
    {

        $failures = count($inventory->getFailed());
        $succeeded = count($inventory->getSucceeded());

        return sprintf("%s new test%s failures, %s new test%s succeeded", $failures, $failures != 1 ? 's' : '',
            $succeeded, $succeeded != 1 ? 's' : '');
    }
}
