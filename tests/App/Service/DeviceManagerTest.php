<?php

namespace App\Service;

use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DeviceManagerTest extends KernelTestCase
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * The method should store the graph as following
     *
     * ['A' => ['B' => 10, 'C' => 20],
     * 'B' => ['A' => 10, 'D' => 100],
     * 'C' => ['A' => 20, 'D' => 30],
     * 'D' => ['B' => 100, 'C' => 30, 'E' => 10],
     * 'E' => ['D' => 10, 'F' => 1000],
     * 'F' => ['E' => 1000]]
     *
     * @throws Exception
     */
    public function testStoreAsGraph()
    {
        $data = [
            ['A', 'B', 10],
            ['A', 'C', 20],
            ['B', 'D', 100],
            ['C', 'D', 30],
            ['D', 'E', 10],
            ['E', 'F', 1000]
        ];
        $expectedGraph = [
            'A' => ['B' => 10, 'C' => 20],
            'B' => ['A' => 10, 'D' => 100],
            'C' => ['A' => 20, 'D' => 30],
            'D' => ['B' => 100, 'C' => 30, 'E' => 10],
            'E' => ['D' => 10, 'F' => 1000],
            'F' => ['E' => 1000]
        ];

        self::bootKernel();
        $container = static::getContainer();
        /**@var DeviceManagerInterface $deviceManager */
        $deviceManager = $container->get(DeviceManager::class);
        $graph = $deviceManager->storeAsGraph($data);
        $this->assertEquals($expectedGraph, $graph);
    }

    /**
     * The method should return an array which include the latency field & stack obj.
     *
     * @throws Exception
     */
    public function testShortestPath()
    {
        $graph = [
            'A' => ['B' => 10, 'C' => 20],
            'B' => ['A' => 10, 'D' => 100],
            'C' => ['A' => 20, 'D' => 30],
            'D' => ['B' => 100, 'C' => 30, 'E' => 10],
            'E' => ['D' => 10, 'F' => 1000],
            'F' => ['E' => 1000]
        ];

        self::bootKernel();
        $container = static::getContainer();
        /**@var DeviceManagerInterface $deviceManager */
        $deviceManager = $container->get(DeviceManager::class);
        $result = $deviceManager->shortestPath('A', 'F', $graph);
        $this->assertEquals(1060, $result['latency']);
        $stack = $result['path'];
        $stack->rewind();
        $path = [];
        while ($stack->valid()) {
            $path [] = $stack->current();
            $stack->next();
        }
        $this->assertEquals(['A','C','D','E','F'], $path);
    }
}