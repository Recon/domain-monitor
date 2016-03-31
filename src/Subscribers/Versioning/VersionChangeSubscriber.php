<?php


namespace Subscribers\Versioning;

use Events\VersionChangeEvent;
use Propel\Generator\Config\GeneratorConfig;
use Propel\Generator\Manager\MigrationManager;
use Propel\Generator\Util\SqlParser;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Util\PropelConnectionConfigFactory;


class VersionChangeSubscriber implements EventSubscriberInterface
{
    /**
     * @var PropelConnectionConfigFactory
     */
    private $propelConnectionConfig;

    /**
     * VersionChangeSubscriber constructor.
     *
     * @param PropelConnectionConfigFactory $propelConnectionConfig
     */
    public function __construct(PropelConnectionConfigFactory $propelConnectionConfig)
    {
        $this->propelConnectionConfig = $propelConnectionConfig;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            VersionChangeEvent::NAME => [
                ['executePropelMigration', 10],
            ],
        ];
    }

    public function executePropelMigration(VersionChangeEvent $event)
    {
        $generatorConfig = new GeneratorConfig(null, null);

        $manager = new MigrationManager();
        $manager->setGeneratorConfig($generatorConfig);

        $configOptions = [];
        $configOptions['propel']['paths']['migrationDir'] = APP_DIR . '/generated-migrations';
        $configOptions['propel']['database']['connections']['default'] = $this->propelConnectionConfig->create();
        $generatorConfig = new GeneratorConfig(null, $configOptions);

        $manager = new MigrationManager();
        $manager->setGeneratorConfig($generatorConfig);

        $connections = [
            'default' => $this->propelConnectionConfig->create(),
        ];
        $manager->setConnections($connections);
        $manager->setMigrationTable($generatorConfig->getSection('migrations')['tableName']);
        $manager->setWorkingDirectory($generatorConfig->getSection('paths')['migrationDir']);

        if (!$manager->getFirstUpMigrationTimestamp()) {
            $event->addMessage('All migrations were already executed - nothing to migrate.');
        }

        $timestamps = $manager->getValidMigrationTimestamps();
        if (count($timestamps) > 1) {
            $event->addMessage(sprintf('%d migrations to execute', count($timestamps)));
        }

        foreach ($timestamps as $timestamp) {
            $event->addMessage(
                sprintf('Executing migration %s up', $manager->getMigrationClassName($timestamp))
            );

            $migration = $manager->getMigrationObject($timestamp);
            if (property_exists($migration, 'comment') && $migration->comment) {
                $event->addMessage(sprintf('%s', $migration->comment));
            }


            foreach ($migration->getUpSQL() as $datasource => $sql) {
                $connection = $manager->getConnection($datasource);
                $conn = $manager->getAdapterConnection($datasource);
                $res = 0;
                $statements = SqlParser::parseString($sql);

                foreach ($statements as $statement) {
                    try {
                        $conn->exec($statement);
                        $res++;
                    } catch (\Exception $e) {
                        $event->addMessage(
                            sprintf('Failed to execute SQL "%s". Continue migration.', $statement)
                        );
                    }
                }

                $event->addMessage(
                    sprintf(
                        '%d of %d SQL statements executed successfully on datasource "%s"',
                        $res,
                        count($statements),
                        $datasource
                    )
                );
            }

            // migrations for datasources have passed - update the timestamp
            // for all datasources
            foreach ($manager->getConnections() as $datasource => $connection) {
                $manager->updateLatestMigrationTimestamp($datasource, $timestamp);
                $event->addMessage(sprintf(
                    'Updated latest migration date to %d for datasource "%s"',
                    $timestamp,
                    $datasource
                ));
            }

            $migration->postUp($manager);
        }
    }


}
