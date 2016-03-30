<?php

namespace Util;

use Swift_MailTransport;
use Swift_Message;
use Swift_SmtpTransport;
use Swift_Transport;
use Symfony\Component\Templating\EngineInterface;
use Util\Config\ConfigLoader;

class MailerFactory
{
    /**
     * @var EngineInterface
     */
    private $templatingEngine;

    /**
     * @var ConfigLoader
     */
    private $config;

    /**
     * @var Swift_Transport
     */
    private $transport;

    /**
     * MailerFactory constructor.
     *
     * @param EngineInterface $templatingEngine
     */
    public function __construct(EngineInterface $templatingEngine, ConfigLoader $config)
    {
        $this->templatingEngine = $templatingEngine;
        $this->config = $config;
    }

    /**
     * @return Mailer
     */
    public function factory()
    {
        $mailer = new Mailer(Swift_Message::newInstance(), $this->templatingEngine);
        $mailer->setTransport($this->getTransport());

        return $mailer;
    }

    /**
     * @return Swift_Transport
     */
    protected function getTransport()
    {
        if (!empty($this->transport)) {
            return $this->transport;
        }

        switch ($this->config->get('mail_transport')) {
            case 'smtp':
                $transport = Swift_SmtpTransport::newInstance(
                    $this->config->get('smtp_server'),
                    $this->config->get('smtp_port')
                );
                $transport->setUsername($this->config->get('smtp_user'));
                $transport->setPassword($this->config->get('smtp_pass'));
                break;
            default:
                $transport = Swift_MailTransport::newInstance();
        }

        $this->transport = $transport;

        return $transport;
    }

}
