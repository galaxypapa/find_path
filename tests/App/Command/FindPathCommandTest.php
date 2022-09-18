<?php

namespace App\Command;

use App\Service\File\CsvLoader;
use App\Tests\Util;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FindPathCommandTest extends KernelTestCase
{
    /**
     * Given a "quit" should return false, otherwise return true.
     *
     * @throws ReflectionException
     */
        public function testIsWaitForInput()
        {
            self::bootKernel();
            $container = static::getContainer();
            $csvLoader = $container->get(CsvLoader::class);
            $this->assertFalse(Util::callMethod(new FindPathCommand($csvLoader),'isWaitForInput',['input'=>'quit']));
            $this->assertTrue(Util::callMethod(new FindPathCommand($csvLoader),'isWaitForInput',['input'=>'a b 100']));
        }
}
