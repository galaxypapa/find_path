<?php

namespace App\Tests;

use ReflectionException;

class Util
{
    /**
     * Test private or protect method
     *
     * @param $obj
     * @param string $name
     * @param array $args
     * @return mixed
     * @throws ReflectionException
     */
    public static function callMethod($obj, string $name, array $args = []) {
        $class = new \ReflectionClass($obj);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method->invokeArgs($obj, $args);
    }
}