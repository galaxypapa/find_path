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
        $arr = [];
        $file = fopen($path, 'r');
        while (($result = fgetcsv($file)) !== false) {
            $arr[] = $result;
        }
        fclose($file);
        return $arr;
    }
}