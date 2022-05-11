<?php

namespace VI\PackagistPublish\Commands;

class PublishResult
{
    private int $publishId;
    private string $packageRoot;
    private string $packageName;
    private string $packageVersion;
    private string $gitRoot;
    private string $archiveFile;

    /**
     * @param int $publishId
     * @param string $packageRoot
     * @param string $packageName
     * @param string $packageVersion
     * @param string $gitRoot
     * @param string $archiveFile
     */
    public function __construct(
        int $publishId,
        string $packageRoot,
        string $packageName,
        string $packageVersion,
        string $gitRoot,
        string $archiveFile
    ) {
        $this->publishId = $publishId;
        $this->packageRoot = $packageRoot;
        $this->packageName = $packageName;
        $this->packageVersion = $packageVersion;
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
     * @noinspection PhpUnused
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
    public function getPackageVersion(): string {
        return $this->packageVersion;
    }

    /**
     * @return string
     * @noinspection PhpUnused
     */
    public function getGitRoot(): string {
        return $this->gitRoot;
    }

    /**
     * @return string
     * @noinspection PhpUnused
     */
    public function getArchiveFile(): string {
        return $this->archiveFile;
    }

}