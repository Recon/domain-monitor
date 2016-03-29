<?php


namespace Util\Config;


class AbstractConfig
{

    /**
     * @var string
     */
    protected $directory;

    /**
     * @var string
     */
    protected $filename = 'app.yml';

    /**
     * @var string
     */
    protected $filepath = '';

    public function __construct($directory)
    {
        $this->directory = $directory;
        $this->filepath = $this->directory . '/' . $this->filename;
    }
}
