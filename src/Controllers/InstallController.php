<?php

namespace Controllers;

use Events\VersionChangeEvent;
use Exceptions\HTTP\Error404;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Util\Config\ConfigLoader;
use Util\Config\Install\ConfigFileWriter;
use Util\Config\Install\InstallConfiguration;
use Util\EnvironmentCheck;

class InstallController extends AbstractController
{

    /**
     * @var ConfigFileWriter
     */
    private $configWriter;

    /**
     * @var ConfigLoader
     */
    private $configLoader;

    const ALLOW_WRITE_ONLY_WHEN_MISSING = false;

    public function init()
    {
        $this->configWriter = $this->container->get('config_writer');
        $this->configLoader = $this->container->get('config_loader');
    }

    public function showInstallPage()
    {
        $this->denyRequestOnExistingFile();

        $body = $this->templateEngine->render('install', [
            'data'               => $this->session->get('install_config') ?: new InstallConfiguration(),
            'form_errors'        => $this->session->get('install_form_errors', []),
            'environment_checks' => new EnvironmentCheck(),
        ]);

        $this->session->remove('install_form_errors');
        $this->session->remove('install_config');

        return new Response($body);
    }

    public function submitInstallConfiguration()
    {
        $this->denyRequestOnExistingFile();

        /* @var $validator ValidatorInterface */
        $validator = $this->container->get('validator');

        $config = new InstallConfiguration();
        $config->setData($data = $this->request->request->all());

        $groups = array_merge(['Default'], ($config->mail_transport === 'smtp' ? ['smtp'] : []));
        $violations = $validator->validate($config, null, $groups);
        $errors = [];
        if (count($violations)) {
            foreach ($violations as $violation) {
                $errors[] = $violation->getMessage();
            }
        }

        if (count($errors)) {
            $this->session->set('install_form_errors', $errors);
            $this->session->set('install_config', $config);

            return new RedirectResponse('install');
        } else {
            $this->saveConfiguration($data);

            return new RedirectResponse('install/done');
        }
    }

    public function submitInstallCompletePage()
    {
        $body = $this->templateEngine->render('install_done');

        return new Response($body);
    }


    protected function saveConfiguration($data)
    {
        unset($data['admin_email'], $data['admin_pass'], $data['admin_pass_repeat']);
        $this->configWriter->writeData($data);

        $event = new VersionChangeEvent();
        $this->container->get('event_dispatcher')->dispatch(VersionChangeEvent::NAME, $event);
    }

    protected function denyRequestOnExistingFile()
    {
        if (static::ALLOW_WRITE_ONLY_WHEN_MISSING && $this->configLoader->isLoaded()) {
            throw new Error404();
        }
    }
}
