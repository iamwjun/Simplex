<?php
namespace App\Config;

use Symfony\Component\Finder\Finder;

class Path
{
    /**
     * Get the file's real path
     * @param null $key iterator's key
     * @return mixed $key's path
     */
    public static function getPath($key = null){
        $finder = new Finder();
        $finder->files()->in(__DIR__);
        $path = iterator_to_array($finder, false);
        return array_key_exists($key, $path) ? $path[$key]->getRealPath() : false;
    }
}