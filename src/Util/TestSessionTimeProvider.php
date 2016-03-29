<?php

namespace Util;

use DateTime;

/**
 * Provides a consistent timestamp for testing sessions
 *
 * Due to the fact that some test might be having a long timeout, updating the timestamp of various records with
 * the current date/time might lead to innacuracy throughout the application. By using the datetime of this provider,
 * we ensure a unique and consistent timestamp
 */
class TestSessionTimeProvider
{
    /**
     * @var DateTime
     */
    protected $time;

    protected static $instance;

    private function __construct()
    {
        $this->time = new DateTime();
    }

    public static function instance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new TestSessionTimeProvider();
        }

        return static::$instance;
    }

    /**
     * @return DateTime
     */
    public function getTime()
    {
        return $this->time;
    }
}
