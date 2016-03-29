<?php


namespace Util\Config\Install;


use Symfony\Component\Yaml\Dumper;
use Util\Config\AbstractConfig;

class ConfigFileWriter extends AbstractConfig
{
    /**
     * @return bool
     */
    public function canWrite()
    {
        if (file_exists($this->filepath)) {
            return is_writable($this->filepath);
        } else {
            return is_writable($this->directory);
        }
    }

    /**
     * @param array $data
     */
    public function writeData(array $data)
    {
        $dumper = new Dumper();
        $yaml = $dumper->dump($data, 1);

        file_put_contents($this->filepath, $yaml);
    }

}
