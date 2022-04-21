<?php

namespace VI\PackagistPublish\Commands;

use Exception;
use VI\PackagistPublish\Utils\Git;
use VI\PackagistPublish\Utils\PackageRoot;

class ArchiveCommand
{
    private string $archiveName = 'archive';

    /**
     * @throws Exception
     */
    private function getPackageRoot($composerFile): string {
        if (is_string($composerFile) && file_exists($composerFile)) {
            $startPath = dirname($composerFile);
        } else {
            $startPath = getcwd();
        }
        $packageRoot = new PackageRoot();
        return $packageRoot->find($startPath);
    }

    private function getDestination($destinationFile, $packageRoot): string {
        if (is_string($destinationFile)) {
            if (file_exists($destinationFile)) {
                if (is_dir($destinationFile)) {
                    return $destinationFile . DS . $this->archiveName . '.zip';
                } else {
                    return $destinationFile;
                }
            } else {
                return $destinationFile;
            }
        } else {
            return $packageRoot . DS . $this->archiveName . '.zip';
        }
    }

    /**
     * @param $composerFile
     * @param $destinationFile
     * @return ArchiveResult
     * @throws Exception
     */
    public function run($composerFile, $destinationFile): ArchiveResult {
        $packageRoot = $this->getPackageRoot($composerFile);
        $git = new Git();
        // If we cannot get version; than we do not have git installed
        $git->getVersion();
        if (!$git->hasChanges($packageRoot)) {
            $gitRoot = $git->getRoot($packageRoot);
            $destination = $this->getDestination($destinationFile, $packageRoot);
            $git->archive($gitRoot, $packageRoot, $destination);
            return new ArchiveResult(
                $packageRoot, $gitRoot, $destination
            );
        } else {
            throw new Exception('Package root contains changes; commit those and try again');
        }
    }
}