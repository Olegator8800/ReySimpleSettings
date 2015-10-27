<?php

namespace Rey\SimpleSettings;

use Rey\SimpleSettings\ParserFileParameters\ParserParametersInterface;
use Rey\SimpleSettings\ParserFileParameters\IniParametersParser;

class SettingsExtender
{
    /**
     * @var null|ParserInterface
     */
    private $parser = null;

    /**
     * @var string
     */
    private $parametrFilePath;

    /**
     * @var mixed
     */
    private $collectDirectoryPath;

    /**
     * @var array
     */
    private $settings = array();

    /**
     * @param string $parametrFilePath
     * @param mixed $collectDirectoryPath
     */
    public function __construct($parametrFilePath, $collectDirectoryPath = null)
    {
        $this->parametrFilePath = $parametrFilePath;
        $this->collectDirectoryPath = $collectDirectoryPath;
    }

    /**
     * @param IniParametersParser $parser
     */
    public function setParser(IniParametersParser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @return IniParametersParser
     */
    public function getParser()
    {
        if ($this->parser === null) {
            $this->parser = new IniParametersParser();
        }

        return $this->parser;
    }

    /**
     * @return string
     */
    protected function getParametrFilePath()
    {
        return $this->parametrFilePath;
    }

    /**
     * @return string
     */
    protected function getCollectDirectoryPath()
    {
        return $this->collectDirectoryPath;
    }

    /**
     * @return string
     */
    protected function getCollectDirectoryFilePattern()
    {
        $parser = $this->getParser();
        return $parser->getFilePattern();
    }

    /**
     * @param array $settings
     */
    public function setSettings(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @return array
     */
    public function getExtendedSettings()
    {
        $parametrFilePath = $this->getParametrFilePath();
        $settings = $this->getSettings();

        $parametrs = $this->parseFile($parametrFilePath);
        $parametrs = $this->extendParametrs($parametrs);
        $parametrs = $this->prepareParametrsValue($parametrs);

        return array_replace_recursive($settings, $parametrs);
    }

    /**
     * @param  array  $parametrs
     *
     * @return array
     */
    protected function extendParametrs(array $parametrs)
    {
        $collectDirectoryPath = $this->getCollectDirectoryPath();
        $filePattern = $this->getCollectDirectoryFilePattern();

        if ($collectDirectoryPath && is_dir($collectDirectoryPath)) {
            $extendParametrs = glob($collectDirectoryPath.$filePattern, GLOB_ERR);

            foreach ($extendParametrs as $file) {
                $data = $this->parseFile($file);
                $parametrs = array_replace_recursive($parametrs, $data);
            }
        }

        return $parametrs;
    }

    /**
     * @param  array  $parametrs
     *
     * @return array
     */
    protected function prepareParametrsValue(array $parametrs)
    {
        $prepareParametrs = [];

        foreach ($parametrs as $section => $value) {
            $readOnly = false;

            if (isset($value['readonly'])) {
                $readOnly = (bool) $value['readonly'];
                unset($value['readonly']);
            }

            if ($section == 'connections') {
                $value = array('default' => $value);
            }

            if ($section == 'exception_handling') {
                $value['debug'] = (bool) $value['debug'];
            }

            $prepareParametrs[$section] = array('value' => $value, 'readonly' => $readOnly);
        }

        return $prepareParametrs;
    }

    /**
     * @param  string $file
     *
     * @return array
     *
     * @throws \RuntimeException If file can not be read
     */
    protected function parseFile($file)
    {
        if (!file_exists($file)) {
            throw new \RuntimeException(sprintf('Required (%s) file not found', $file));
        }

        if (!is_readable($file)) {
            throw new \RuntimeException(sprintf('Required (%s) not have permission to read', $file));
        }

        $parser = $this->getParser();
        $data = $parser->parseFile($file);

        return $data;
    }
}
