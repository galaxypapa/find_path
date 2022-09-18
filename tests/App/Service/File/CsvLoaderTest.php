<?php

namespace App\Service\File;


use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CsvLoaderTest extends KernelTestCase
{
    /**
     * After calling the load method, it should return an array & its value
     * should be the same as those in the csv file.
     *
     * @throws Exception
     */
    public function testLoad()
    {
        self::bootKernel();
        $container = static::getContainer();
        $csvLoader = $container->get(CsvLoader::class);
        $data = $csvLoader->load('latency.csv');
        $this->assertIsArray($data);
        $this->assertEquals([
            ['A', 'B', 10],
            ['A', 'C', 20],
            ['B', 'D', 100],
            ['C', 'D', 30],
            ['D', 'E', 10],
            ['E', 'F', 1000]
        ], $data);
    }
}
