<?php

use Commands\CheckDomains;
use Commands\FillDummyData;
use Commands\GenerateInternalMetadata;
use Symfony\Component\Console\Application;

global $container;

$application = new Application();
$application->add(new GenerateInternalMetadata());
$application->add(new FillDummyData($container->get('auth.encoder')));
$application->add(new CheckDomains($container));
$application->run();
