<?php

namespace App\Containers\AppSection\Authorization\Tests\Unit\Values;

use App\Containers\AppSection\Authentication\Values\IncomingLoginField;
use App\Containers\AppSection\Authorization\Tests\UnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;

#[Group('authorization')]
#[CoversClass(IncomingLoginField::class)]
final class IncomingLoginFieldTest extends UnitTestCase
{
    public function testToArray()
    {
        $value = new IncomingLoginField('email', 'gandalf@the.grey');
        $array = $value->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('email', $array);
        $this->assertSame('gandalf@the.grey', $array['email']);
    }

    public function testToString()
    {
        $value = new IncomingLoginField('email', 'gandalf@the.grey');
        $string = (string) $value;

        $this->assertSame('email', $string);
    }
}
