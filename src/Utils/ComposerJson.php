<?php

namespace VI\PackagistPublish\Utils;

use Exception;

class ComposerJson
{
    private string $composerPath;

    public function __construct(string $composerPath) {
        $this->composerPath = $composerPath;
    }

    /**
     * @throws Exception
     */
    public function parse() {
        if (file_exists($this->composerPath)) {
            $composer = file_get_contents($this->composerPath);
            return json_decode($composer, true);
        } else {
            throw new Exception('Composer.json file could not be found at ' . $this->composerPath);
        }
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getVersion(): string {
        $composerJson = $this->parse();
        return $composerJson['version'];
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getName(): string {
        $composerJson = $this->parse();
        return $composerJson['name'];
    }
}