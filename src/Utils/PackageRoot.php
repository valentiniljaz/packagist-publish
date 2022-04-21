<?php

namespace VI\PackagistPublish\Utils;

use Exception;

class PackageRoot
{
    /**
     * @param $startPath
     * @return string
     * @throws Exception
     */
    public function find($startPath): string {
        while($startPath !== DS) {
            if (file_exists($startPath . DS . 'composer.json')) {
                return $startPath;
            } else {
                $startPath = dirname($startPath);
            }
        }
        throw new Exception('Package root could not be found for ' . $startPath);
    }
}