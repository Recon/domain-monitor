<?php

namespace Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Generates javascript files which contains various status codes used by the app
 */
class GenerateInternalMetadata extends Command
{

    protected function configure()
    {
        $this
            ->setName('app:generate_metadata')
            ->setDescription('Generates javascript files which contains various status codes used by the app');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $content = 'var monitorMetadata = {};' . PHP_EOL;

        $content .= "monitorMetadata.domainStatuses = " . json_encode($this->getDomainRecordStatuses()) . ';' . PHP_EOL;
        $content .= "monitorMetadata.testTypes = " . json_encode($this->getTestTypes()) . ';' . PHP_EOL;
        $content .= "monitorMetadata.testTypesNames = " . json_encode($this->getTestTypesNames(),
                JSON_FORCE_OBJECT) . ';' . PHP_EOL;

        $file = new \SplFileObject($path = __DIR__ . '/../../public_html/js/monitor-metadata.js', 'w');
        $file->fwrite($content);

        $output->writeln(sprintf("<info>A new file has been generated at %s</info>", realpath($path)));
    }

    protected function getDomainRecordStatuses()
    {
        $reflection = new \ReflectionClass(\Models\Domain::class);

        $statuses = $reflection->getConstants();

        foreach ($statuses as $k => $v) {
            if (strpos($k, 'STATUS_') !== 0) {
                unset($statuses[$k]);
            }
        }

        return $statuses;
    }

    protected function getTestTypes()
    {
        $reflection = new \ReflectionClass(\Models\Test::class);

        $types = $reflection->getConstants();

        foreach ($types as $k => $v) {
            if (strpos($k, 'TYPE_') !== 0) {
                unset($types[$k]);
            }
        }

        return $types;
    }

    protected function getTestTypesNames()
    {
        $types = $this->getTestTypes();
        array_walk($types, function (&$value) {
            $value = (int)($value);
        });

        $types = array_flip($types);
        array_walk($types, function (&$value) {
            $value = str_replace('TYPE_', '', $value);
        });

        return $types;
    }

}
