<?php

namespace App\Containers\AppSection\Authorization\Tests\Unit\Data\Seeders;

use App\Containers\AppSection\Authorization\Data\Seeders\AuthorizationDefaultUsersSeeder_4;
use App\Containers\AppSection\User\Actions\CreateAdminAction;
use App\Containers\AppSection\User\Tests\UnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;

#[Group('authorization')]
#[CoversClass(AuthorizationDefaultUsersSeeder_4::class)]
final class AuthorizationDefaultUsersSeeder_4Test extends UnitTestCase
{
    private array $userData = [
        'email' => 'admin@admin.com',
        'password' => 'admin',
        'name' => 'Super Admin',
    ];

    public function testSeederCallsCorrectAction()
    {
        $actionMock = $this->mock(CreateAdminAction::class);
        $actionMock->expects()->run($this->userData)->once();

        $this->seed(AuthorizationDefaultUsersSeeder_4::class);
    }

    public function testDatabaseHasPermissions()
    {
        $userData = $this->userData;
        unset($userData['password']);

        $this->assertDatabaseHas('users', $userData);
    }
}
