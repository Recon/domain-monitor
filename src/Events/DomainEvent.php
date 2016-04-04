<?php

namespace Events;

use Models\Domain;
use Symfony\Component\EventDispatcher\Event;

class DomainEvent extends Event
{

    const NAME_ADDED = 'domain.added';
    const NAME_UPDATED = 'domain.updated';

    /**
     * @var Domain
     */
    private $domain;

    /**
     * DomainEvent constructor.
     *
     * @param Domain $domain
     */
    public function __construct(Domain $domain)
    {
        $this->domain = $domain;
    }

    /**
     * @return Domain
     */
    public function getDomain()
    {
        return $this->domain;
    }

}
