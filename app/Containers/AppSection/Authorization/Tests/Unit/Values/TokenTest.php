<?php

namespace App\Containers\AppSection\Authorization\Tests\Unit\Values;

use App\Containers\AppSection\Authentication\Values\Token;
use App\Containers\AppSection\Authorization\Tests\UnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;

#[Group('authorization')]
#[CoversClass(Token::class)]
final class TokenTest extends UnitTestCase
{
    public function testGetFakeValue()
    {
        $fakeValue = Token::fake();

        $this->assertIsString($fakeValue->tokenType);
        $this->assertIsInt($fakeValue->expiresIn);
        $this->assertIsString($fakeValue->tokenType);
        $this->assertIsString($fakeValue->tokenType);
    }
}
