<?php

namespace App\Containers\AppSection\Authorization\Tests\Unit\Data\Seeders;

use App\Containers\AppSection\Authorization\Data\Seeders\AuthorizationRolesSeeder_2;
use App\Containers\AppSection\Authorization\Tasks\CreateRoleTask;
use App\Containers\AppSection\User\Tests\UnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;

#[Group('authorization')]
#[CoversClass(AuthorizationRolesSeeder_2::class)]
final class AuthorizationRolesSeeder_2Test extends UnitTestCase
{
    private array $roles;

    protected function setUp(): void
    {
        parent::setUp();
        foreach (array_keys(config('auth.guards')) as $guardName) {
            $this->addRole(config('appSection-authorization.admin_role'), 'Administrator', 'Administrator Role', $guardName);
        }
    }

    public function testSeederCallsCorrectTask()
    {
        $taskMock = $this->mock(CreateRoleTask::class);
        foreach ($this->roles as $role) {
            $taskMock->expects()->run(
                $role['name'],
                $role['description'],
                $role['display_name'],
                $role['guard_name'],
            )->once();
        }

        $this->artisan('db:seed', [
            '--class' => AuthorizationRolesSeeder_2::class,
        ])->assertSuccessful();
    }

    public function testDatabaseHasPermissions()
    {
        $tableNames = config('permission.table_names');

        foreach ($this->roles as $permission) {
            $this->assertDatabaseHas($tableNames['roles'], $permission);
        }
    }

    private function addRole(string $key, string|null $description = null, string|null $displayName = null, string $guardName = 'api'): void
    {
        $this->roles[] = [
            'name' => $key,
            'description' => $description,
            'display_name' => $displayName,
            'guard_name' => $guardName,
        ];
    }
}
