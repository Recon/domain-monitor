<?php

namespace Util;

use Swift_Message;
use Symfony\Component\Templating\EngineInterface;

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
     * @var \Swift_Transport
     */
    private $transport;

    /**
     * Mailer constructor.
     *
     * @param Swift_Message   $message
     * @param EngineInterface $templatingEngine
     */
    public function __construct(Swift_Message $message, EngineInterface $templatingEngine)
    {
        $this->message = $message;
        $this->templatingEngine = $templatingEngine;

        $this->message->setFrom('websitemonitor@' . gethostname());
    }

    /**
     * @return Swift_Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param       $template
     * @param array $data
     */
    public function renderMessageBody($template, array $data = [])
    {
        $this->message->setBody($this->templatingEngine->render($template, $data), 'text/html');
    }

    public function send()
    {
        $mailer = \Swift_Mailer::newInstance($this->transport);
        $mailer->send($this->getMessage());
    }

    public function setTransport(\Swift_Transport $transport)
    {
        $this->transport = $transport;
    }

}
