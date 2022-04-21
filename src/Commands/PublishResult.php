<?php

namespace VI\PackagistPublish\Commands;

class PublishResult
{
    private int $publishId;
    private string $packageRoot;
    private string $packageName;
    private string $gitRoot;
    private string $archiveFile;

    /**
     * @param string $packageRoot
     * @param string $packageName
     * @param string $gitRoot
     * @param string $archiveFile
     */
    public function __construct(
        int $publishId,
        string $packageRoot,
        string $packageName,
        string $gitRoot,
        string $archiveFile
    ) {
        $this->publishId = $publishId;
        $this->packageRoot = $packageRoot;
        $this->packageName = $packageName;
        $this->gitRoot = $gitRoot;
        $this->archiveFile = $archiveFile;
    }

    /**
     * @return int
     */
    public function getPublishId(): int {
        return $this->publishId;
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
    public function getPackageName(): string {
        return $this->packageName;
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