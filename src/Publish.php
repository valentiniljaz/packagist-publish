<?php

namespace VI\PackagistPublish;

use Exception;
use VI\PackagistPublish\Commands\PublishCommand;
use VI\PackagistPublish\Utils\ComposerJson;
use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

require 'Utils' . DIRECTORY_SEPARATOR . 'Const.php';

class Publish extends CLI
{
    protected function setup(Options $options) {
        $options->setHelp('Publish your package to Packagist.com using API');
        $options->registerOption('composer', 'Path to composer.json', 'c', 'path');
        $options->registerOption('archive', 'Path to the location where archive will be saved', 'a', 'path');
        $options->registerOption('apiKey', 'Packagist.com API Key', 'k', 'key');
        $options->registerOption('apiSecret', 'Packagist.com API Secret', 's', 'secret');
        $options->registerOption('version', 'Print version', 'v');
    }

    private function printVersion() {
        try {
            $composerJson = new ComposerJson(dirname(__FILE__) . DS . '..' . DS . 'composer.json');
            $this->info($composerJson->getVersion());
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    protected function main(Options $options) {
        if ($options->getOpt('version')) {
            $this->printVersion();
        } else {
            try {
                $publish = (new PublishCommand())->run(
                    $options->getOpt('composer'),
                    $options->getOpt('archive'),
                    $options->getOpt('apiKey'),
                    $options->getOpt('apiSecret')
                );
                $this->info('Package ' . $publish->getPackageName() . ' was published (id: ' . $publish->getPublishId() .')');
            } catch (Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }
}