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

    /**
     * Mailer constructor.
     * @param Swift_Message $message
     * @param EngineInterface $templatingEngine
     */
    public function __construct(Swift_Message $message, EngineInterface $templatingEngine)
    {
        $this->message = $message;
        $this->templatingEngine = $templatingEngine;

        $this->message->setFrom('websitemonitor@localhost');
    }

    /**
     * @return Swift_Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param $template
     * @param array $data
     */
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
