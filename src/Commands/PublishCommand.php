<?php

namespace VI\PackagistPublish\Commands;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use VI\PackagistPublish\Utils\ComposerJson;
use VI\PackagistPublish\Utils\Packagist;

class PublishCommand
{

    /**
     * @param $varName
     * @param $varVal
     * @return string
     * @throws Exception
     */
    private function getEnvVar($varName, $varVal): string {
        if (is_string($varVal) && !empty($varVal)) {
            return $varVal;
        } else {
            $envVarVal = getenv('PACKAGIST_' . $varName);
            if (!empty($envVarVal)) {
                return $envVarVal;
            } else {
                throw new Exception('Provide value for API ' . $varName);
            }
        }
    }

    /**
     * @param $composerFile
     * @param $archiveFile
     * @param $apiKey
     * @param $apiSecret
     * @return PublishResult
     * @throws Exception
     * @throws GuzzleException
     */
    public function run($composerFile, $archiveFile, $apiKey, $apiSecret): PublishResult {
        $apiKey = $this->getEnvVar('KEY', $apiKey);
        $apiSecret = $this->getEnvVar('SECRET', $apiSecret);
        $archive = (new ArchiveCommand())->run($composerFile, $archiveFile);
        $packageName = (new ComposerJson($archive->getPackageRoot() . DS . 'composer.json'))->getName();

        (new Packagist())->publish($packageName, $archive->getArchiveFile(), $apiKey, $apiSecret);

        return new PublishResult(
            $archive->getPackageRoot(),
            $packageName,
            $archive->getGitRoot(),
            $archive->getArchiveFile()
        );
    }
}