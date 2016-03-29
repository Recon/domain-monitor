<?php

namespace Commands;

use Models\Account;
use Models\Domain;
use Models\User;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

/**
 * Generates javascript files which contains various status codes used by the app
 */
class FillDummyData extends Command
{

    /**
     * @var EncoderFactory
     */
    private $encoderFactory;
    private $domains = ['example.com', 'google.com', 'reddit.com'];

    public function __construct(EncoderFactory $encoderFactory)
    {
        parent::__construct();

        $this->encoderFactory = $encoderFactory;
    }

    protected function configure()
    {
        $this
            ->setName('app:fill_dummy_data')
            ->setDescription('Fills the database with some dummy data');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $account = new Account();
        $account->setName("Test Account")
            ->save();

        $admin = new User();
        $admin->setUsername('test@example.com')
            ->setEmail('test@example.com')
            ->setSalt('123456')
            ->setPassword($this->encoderFactory->getEncoder($admin)->encodePassword('test', $admin->getSalt()))
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER'])
            ->setAccount($account)
            ->save();

        $user = new User();
        $user->setUsername('user@example.com')
            ->setEmail('user@example.com')
            ->setSalt('123456')
            ->setPassword($this->encoderFactory->getEncoder($user)->encodePassword('test', $user->getSalt()))
            ->setRoles(['ROLE_USER'])
            ->setAccount($account)
            ->save();

        foreach ($this->domains as $uri) {
            $domain = new Domain();
            $domain->setAccount($account)
                ->setStatus(Domain::STATUS_UNKNOWN)
                ->setUri($uri)
                ->setIsEnabled(true)
                ->save();

            $user->addDomain($domain)->save();
            $admin->addDomain($domain)->save();
        }
        $output->writeln("<info>Done!</info>");
    }

    protected function fillAccounts()
    {

    }

    protected function fillUsers()
    {

    }

}
