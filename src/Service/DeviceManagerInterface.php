<?php

namespace App\Service;

interface DeviceManagerInterface
{
    function storeAsGraph(array $latencyInfo): array;

    function shortestPath(string $from, string $to, array $graph): array;
}