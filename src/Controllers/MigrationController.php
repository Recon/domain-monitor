<?php

namespace Controllers;

use Events\VersionChangeEvent;
use Exceptions\HTTP\Error404;
use Symfony\Component\HttpFoundation\Response;


class MigrationController extends AbstractController
{

    /**
     * Executes pending propel migration using the web interface
     *
     * @return Response
     * @throws \Exception
     */
    public function migrate()
    {

        if (!$this->permissionChecker->isAdmin()) {
            throw new Error404;
        }

        $event = new VersionChangeEvent();
        $this->container->get('event_dispatcher')->dispatch(VersionChangeEvent::NAME, $event);

        ob_start();

        foreach ($event->getMessages() As $message) {
            dump($message);
        }

        dump("Migration finished");

        return new Response(ob_get_clean());
    }

}
