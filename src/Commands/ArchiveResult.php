<?php

namespace VI\PackagistPublish\Commands;

class ArchiveResult
{
    private string $packageRoot;
    private string $gitRoot;
    private string $archiveFile;

    /**
     * @param string $packageRoot
     * @param string $gitRoot
     * @param string $archiveFile
     */
    public function __construct(string $packageRoot, string $gitRoot, string $archiveFile) {
        $this->packageRoot = $packageRoot;
        $this->gitRoot = $gitRoot;
        $this->archiveFile = $archiveFile;
    }

    /**
     * @return string
     */
    public function getPackageRoot(): string {
        return $this->packageRoot;
    }

    /**
     * @return string
     */
    public function getGitRoot(): string {
        return $this->gitRoot;
    }

    /**
     * @return string
     */
    public function getArchiveFile(): string {
        return $this->archiveFile;
    }

}