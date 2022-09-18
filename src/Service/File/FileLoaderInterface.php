<?php

namespace App\Service\File;

interface FileLoaderInterface
{
    function load(string $path): array;
}