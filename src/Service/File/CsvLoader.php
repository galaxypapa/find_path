<?php

namespace App\Service\File;

class CsvLoader implements FileLoaderInterface
{

    /**
     * Load data into array from a given csv file.
     *
     * @param string $path
     * @return array
     */
    function load(string $path): array
    {
        $devicePaths = [];
        $file = fopen($path, 'r');
        while (($result = fgetcsv($file)) !== false) {
            $devicePaths[] = $result;
        }
        fclose($file);
        return $devicePaths;
    }
}