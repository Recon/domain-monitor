<?php

namespace Commands;

use DateTime;
use Events\TestSessionFinishedEvent;
use Models\Domain;
use Models\DomainQuery;
use Models\Test;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\Collection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Util\TestEvaluators\AbstractCurlDomainResponseEvaluator;
use Util\Tests\AbstractCurlBatch;
use Util\Tests\HttpBatch;
use Util\Tests\HttpsBatch;

class DomainTestsExecution extends Command
{

    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    /**
     * @var Container
     */
    private $container;

    /**
     * The time when this testing session has been initiated
     *
     * @var DateTime
     */
    private $time;

    /**
     * DomainTestsExecution constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->dispatcher = $this->container->get('event_dispatcher');

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('execute_tests')
            ->setDescription('Executes all the domain tests');
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
        $this->performCurlConnectivityTests($this->container->get('evaluator.http'), new HttpBatch(), $domains,
            Test::TYPE_HTTP);

        $output->writeln("<info>Executing HTTPS tests...</info>");
        $this->performCurlConnectivityTests($this->container->get('evaluator.https'), new HttpsBatch(), $domains,
            Test::TYPE_HTTPS);

        $this->dispatcher->dispatch(TestSessionFinishedEvent::NAME, new TestSessionFinishedEvent());
    }

    /**
     *
     * @param AbstractCurlDomainResponseEvaluator $evaluator
     * @param Collection                          $domains
     * @param string                              $type Test type
     */
    protected function performCurlConnectivityTests(
        AbstractCurlDomainResponseEvaluator $evaluator,
        AbstractCurlBatch $tester,
        Collection $domains,
        $type
    ) {

        /* @var $test Test */
        /* @var $domain Domain */
        foreach ($domains As $domain) {
            foreach ($domain->getTests() as $test) {
                if ($test->getTestType() != $type) {
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
