<?php

namespace Commands;

use \Models\Domain;
use \Models\DomainQuery;
use \Models\Test;
use \Propel\Runtime\ActiveQuery\Criteria;
use \Propel\Runtime\Collection\Collection;
use \Symfony\Component\Console\Command\Command;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\DependencyInjection\Container;
use \DateTime;
use \Util\Tests\HttpBatch;

class DomainTestsExecution extends Command
{

    /**
     * @var Container
     */
    private $container;

    /**
     * The time when this testing session has been initiated
     * @var DateTime
     */
    private $time;

    public function __construct(Container $container)
    {
        $this->container = $container;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('execute_tests')
            ->setDescription('Executes all the domain tests')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

        $this->time = new DateTime();

        $domains = DomainQuery::create()
            ->joinWith("Domain.Test", Criteria::LEFT_JOIN)
            ->joinWith("Domain.UsersDomain")
            ->filterByIsEnabled(true)
            ->find();

        $output->writeln(sprintf("<info>Found %s domains</info>", $domains->count()));
        $output->writeln("<info>Executing HTTP tests...</info>");
        $this->performHttpTests($domains);
    }

    /**
     *
     * @param Domain[] $domains
     */
    protected function performHttpTests(Collection $domains)
    {
        $tester = new HttpBatch();
        $evaluator = $this->container->get('evaluator.http');

        /* @var $test \Models\Test */
        /* @var $domain \Models\Domain */
        foreach ($domains As $domain) {
            foreach ($domain->getTests() as $test) {
                if ($test->getTestType() != Test::TYPE_HTTP) {
                    continue;
                }
                
                $tester->addTest($test);
                $test->setLastChecked($this->time);
                $domain->setLastChecked($this->time);
                $domain->save();
            }
        }

        $results = $tester->execute();

        foreach ($results as $result) {
            $evaluator->evaluate($result);
        }
    }

}
