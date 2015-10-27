<?php

namespace Rey\SimpleSettings\Parser;

interface ParserInterface
{
    /**
     * Get pattern matching "globbing"
     *
     * @return string
     */
    public function getFilePattern();

    /**
     * @param  string $filePath
     *
     * @return array
     *
     * @throws \RuntimeException If wrong format of data
     */
    public function parseFile($filePath);
}
