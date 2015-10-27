<?php

namespace Rey\SimpleSettings\Parser;

use Rey\SimpleSettings\Parser\ParserInterface;

class IniParser implements ParserInterface
{
    /**
     * {@inheritdoc}
     */
    public function getFilePattern()
    {
        return '/*.ini';
    }

    /**
     * {@inheritdoc}
     */
    public function parseFile($filePath)
    {
        $data = parse_ini_file($filePath, true);

        if ($data === false) {
            throw new \RuntimeException(sprintf('Error reading file (%s), wrong format of data', $filePath));
        }

        return $data;
    }
}
