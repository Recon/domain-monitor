<?php

namespace Util;

use Symfony\Component\Templating\Loader\FilesystemLoader;
use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;

class TemplatingFactory
{

    /**
     * @return PhpEngine
     */
    public function create()
    {
        $loader = new FilesystemLoader(__DIR__ . '/../Views/%name%.php');

        $templating = new PhpEngine(new TemplateNameParser(), $loader);

        return $templating;
    }

}
