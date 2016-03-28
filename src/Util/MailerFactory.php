<?php

namespace Util;

use Swift_Message;
use Symfony\Component\Templating\EngineInterface;

class MailerFactory
{
    /**
     * @var EngineInterface
     */
    private $templatingEngine;

    /**
     * MailerFactory constructor.
     * @param EngineInterface $templatingEngine
     */
    public function __construct(EngineInterface $templatingEngine)
    {
        $this->templatingEngine = $templatingEngine;
    }

    /**
     * @return Mailer
     */
    public function factory()
    {
        return new Mailer(Swift_Message::newInstance(), $this->templatingEngine);
    }

}
