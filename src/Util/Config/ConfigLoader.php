<?php


namespace Util\Config;


use Exceptions\MissingConfigurationFileException;
use Symfony\Component\Yaml\Parser;

class ConfigLoader extends AbstractConfig
{

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @throws MissingConfigurationFileException
     */
    public function load()
    {
        if (!file_exists($this->filepath) || !is_readable($this->filepath)) {
            throw new MissingConfigurationFileException("Unable to load the configuration file");
        }

        $yaml = file_get_contents($this->filepath);
        $parser = new Parser();

        $this->data = $parser->parse($yaml);
    }

    /**
     * @return bool
     */
    public function isLoaded()
    {
        return !empty($this->data);
    }

    /**
     * @param string $key Configuration key
     * @return mixed Configuration value
     */
    public function get($key)
    {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }
    }

}
