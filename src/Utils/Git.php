<?php

namespace VI\PackagistPublish\Utils;

use Exception;
use Symfony\Component\Process\Process;

class Git
{
    /**
     * @return string
     * @throws Exception
     */
    public function getVersion(): string {
        $process = new Process(['git', '--version']);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new Exception('Git is not installed: ' . $process->getErrorOutput());
        }

        // Output is like "git version 2.36.0"
        // We must split it and take the 3rd element
        return explode(' ', $process->getOutput())[2];
    }

    /**
     * @param string $path
     * @return bool
     * @throws Exception
     */
    public function hasChanges(string $path): bool {
        $process = new Process(['git', 'status', $path]);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new Exception('Git status failed: ' . $process->getErrorOutput());
        }

        // If response contains "nothing to commit" than it has no changes
        // Or if it does not contain "nothing to commit" than it has changes
        return preg_match('/nothing to commit/', $process->getOutput()) === 0;
    }

    /**
     * @param string $startPath
     * @return string
     * @throws Exception
     */
    public function getRoot(string $startPath): string
    {
        while($startPath !== DS) {
            if (file_exists($startPath . DS . '.git')) {
                return $startPath;
            } else {
                $startPath = dirname($startPath);
            }
        }
        throw new Exception('Git root could not be found for ' . $startPath);
    }

    /**
     * @param string $gitRoot
     * @param string $packageRoot
     * @param string $dest
     * @return void
     * @throws Exception
     */
    public function archive(string $gitRoot, string $packageRoot, string $dest) {
        $head = 'HEAD';
        if ($gitRoot !== $packageRoot) {
            // Get relative path from GIT root to current package root
            $head .= ':' . ltrim(substr(realpath($packageRoot), strlen(realpath($gitRoot))), DS);
        }
        $process = new Process(['git', 'archive', '-o', $dest, $head]);
        $process->setWorkingDirectory($gitRoot);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new Exception('Git archive failed (Git: ' . $gitRoot . '; Package: ' . $packageRoot . '; Dest: ' . $dest . '): ' . $process->getErrorOutput());
        }
    }
}