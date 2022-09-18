<?php

namespace App\Command;

use App\Tests\Util;
use PHPUnit\Framework\TestCase;

class FindPathCommandTest extends TestCase
{
    public function testIsWaitForInput()
    {
        $this->assertFalse(Util::callMethod(new FindPathCommand(),'isWaitForInput',['input'=>'quit']));

        $this->assertTrue(Util::callMethod(new FindPathCommand(),'isWaitForInput',['input'=>'a b 100']));
    }
}
