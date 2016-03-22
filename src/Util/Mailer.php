<?php

namespace Util;

use \Swift_Message;
use \Symfony\Component\Templating\EngineInterface;

class Mailer
{

    /**
     * @var EngineInterface
     */
    private $templatingEngine;

    /**
     * @var Swift_Message
     */
    private $message;

    public static function factory(EngineInterface $templatingEngine)
    {
        return new Mailer(Swift_Message::newInstance(), $templatingEngine);
    }

    public function __construct(Swift_Message $message, EngineInterface $templatingEngine)
    {
        $this->message = $message;
        $this->templatingEngine = $templatingEngine;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function renderMessageBody($template, array $data = [])
    {
        $this->message->setBody($this->templatingEngine->render($template, $data), 'text/html');
    }

    public function send()
    {
        /**
         * @todo Split, factory, configuration, multiple mechanism
         */
        $transport = \Swift_SmtpTransport::newInstance('mailtrap.io', '2525');
        $transport->setUsername('449623fa3e18dcea5');
        $transport->setPassword('870b7f224f2c9f');
        $mailer = \Swift_Mailer::newInstance($transport);
        $mailer->send($this->getMessage());
    }

}
