<?php


namespace Util;


use Util\Config\ConfigLoader;

class PropelConnectionConfigFactory
{

    /**
     * @var ConfigLoader
     */
    private $config;

    /**
     * PropelConnectionConfigFactory constructor.
     *
     * @param ConfigLoader $config
     */
    public function __construct(ConfigLoader $config)
    {
        $this->config = $config;
        
    }

    /**
     * Cretes a propel.database.connection.default config item
     *
     * @return array
     */
    public function create()
    {
        return [
            'dsn'       => sprintf(
                'mysql:host=%s;port=%s;dbname=%s',
                $this->config->get('db_host'),
                $this->config->get('db_port'),
                $this->config->get('db_name')),
            'user'      => $this->config->get('db_user'),
            'password'  => $this->config->get('db_pass'),
            'adapter'   => 'mysql',
            'settings'  =>
                [
                    'charset' => 'utf8',
                    'queries' => [],
                ],
            'classname' => '\\Propel\\Runtime\\Connection\\ConnectionWrapper',
        ];
    }
}
