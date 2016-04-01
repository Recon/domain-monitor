<?php

namespace Controllers;

use Events\ConfigLoadEvent;
use Events\VersionChangeEvent;
use Exceptions\HTTP\Error404;
use Models\AccountQuery;
use Models\User;
use Models\UserQuery;
use PDO;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Util\Config\ConfigLoader;
use Util\Config\Install\ConfigFileWriter;
use Util\Config\Install\InstallConfiguration;

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
            'environment_checks' => $this->container->get('environment_check'),
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

        $pdoStatus = $this->probePdoConnection($config);
        if ($pdoStatus !== true) {
            $errors[] = 'SQL connection error: ' . $pdoStatus;
        }

        if (count($errors)) {
            $this->session->set('install_form_errors', $errors);
            $this->session->set('install_config', $config);

            return new RedirectResponse('install');
        } else {
            $this->performConfigurationUpdate($data);
            $this->createAdministrator($data);

            return new RedirectResponse('install/done');
        }
    }

    public function submitInstallCompletePage()
    {
        $body = $this->templateEngine->render('install_done');

        return new Response($body);
    }


    protected function performConfigurationUpdate($data)
    {
        unset($data['admin_email'], $data['admin_pass'], $data['admin_pass_repeat']);
        $this->configWriter->writeData($data);

        $configLoader = $this->container->get('config_loader');
        $configLoader->load();
        $this->container->get('event_dispatcher')->dispatch(ConfigLoadEvent::NAME, new ConfigLoadEvent($configLoader));
        $this->container->get('event_dispatcher')->dispatch(VersionChangeEvent::NAME, new VersionChangeEvent());
    }

    protected function createAdministrator($data)
    {

        $encoderFactory = $this->container->get('auth.encoder');

        /**
         * At this moment there will be only one unique account
         * Maybe in the future the application will support multi-accounts
         */
        $account = AccountQuery::create()
            ->findOneOrCreate();
        $account->setName("Domain Monitor")
            ->save();

        $user = $user = UserQuery::create()
            ->filterByEmail($data['admin_email'])
            ->findOneOrCreate();
        $user->setUsername($data['admin_email'])
            ->setEmail($data['admin_email'])
            ->setSalt(random_str(mt_rand(20, 38)))
            ->setPassword($encoderFactory->getEncoder($user)->encodePassword($data['admin_pass'], $user->getSalt()))
            ->setRoles([
                User::ROLE_USER,
                User::ROLE_ADMIN,
            ])
            ->setAccount($account)
            ->save();

    }

    /**
     * @param InstallConfiguration $config
     * @return mixed Boolean true is successful, or the error message otherwise
     */
    private function probePdoConnection(InstallConfiguration $config)
    {
        $dsn = 'mysql:host=' . $config->db_host . ';dbname=' . $config->db_name . ';port=' . $config->db_port;
        try {
            $connection = new PDO($dsn, $config->db_user, $config->db_pass);
            return true;
        } catch (\PDOException $ex) {
            return $ex->getMessage();
        }
    }

    protected function denyRequestOnExistingFile()
    {
        if (static::ALLOW_WRITE_ONLY_WHEN_MISSING && $this->configLoader->isLoaded()) {
            throw new Error404();
        }
    }

}
