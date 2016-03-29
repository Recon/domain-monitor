<?php

namespace Controllers;

use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{

    public function showHomePage()
    {
        $body = $this->templateEngine->render('index', []);

        return new Response($body);
    }

}
