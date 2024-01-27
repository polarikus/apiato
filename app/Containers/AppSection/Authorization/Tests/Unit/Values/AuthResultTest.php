<?php

namespace App\Containers\AppSection\Authorization\Tests\Unit\Values;

use App\Containers\AppSection\Authentication\Values\AuthResult;
use App\Containers\AppSection\Authentication\Values\Token;
use App\Containers\AppSection\Authorization\Tests\UnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Component\HttpFoundation\Cookie;

#[Group('authorization')]
#[CoversClass(AuthResult::class)]
final class AuthResultTest extends UnitTestCase
{
    public function testGetFakeValue()
    {
        $fakeValue = AuthResult::fake();

        $this->assertInstanceOf(Token::class, $fakeValue->token);
        $this->assertInstanceOf(Cookie::class, $fakeValue->refreshTokenCookie);
    }
}
