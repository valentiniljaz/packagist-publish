<?php

namespace VI\PackagistPublish;

use Exception;
use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;
use VI\PackagistPublish\Commands\ArchiveCommand;
use VI\PackagistPublish\Utils\ComposerJson;

require 'Utils' . DIRECTORY_SEPARATOR . 'Const.php';

class Archive extends CLI
{
    protected function setup(Options $options) {
        $options->setHelp('Archive your package; make it ready to publish');
        $options->registerOption('composer', 'Path to composer.json', 'c', 'path');
        $options->registerOption('archive', 'Path to file where the archive is saved', 'p', 'path');
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
                $archive = (new ArchiveCommand())->run(
                    $options->getOpt('composer'),
                    $options->getOpt('archive')
                );
                $this->info('Archive of ' . $archive->getPackageRoot() . ' was saved to ' . $archive->getArchiveFile());
            } catch (Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }
}