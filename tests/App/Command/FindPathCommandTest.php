<?php

namespace App\Command;

use App\Service\DeviceManager;
use App\Service\File\CsvLoader;
use App\Tests\Util;
use Exception;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FindPathCommandTest extends KernelTestCase
{
    /**
     * Given a "quit" should return false, otherwise return true.
     *
     * @throws ReflectionException
     * @throws Exception
     */
    public function testIsWaitForInput()
    {
        self::bootKernel();
        $container = static::getContainer();
        $csvLoader = $container->get(CsvLoader::class);
        $deviceManager = $container->get(DeviceManager::class);
        $this->assertFalse(Util::callMethod(new FindPathCommand($csvLoader, $deviceManager), 'isWaitForInput', ['input' => 'quit']));
        $this->assertTrue(Util::callMethod(new FindPathCommand($csvLoader, $deviceManager), 'isWaitForInput', ['input' => 'a b 100']));
    }
}
