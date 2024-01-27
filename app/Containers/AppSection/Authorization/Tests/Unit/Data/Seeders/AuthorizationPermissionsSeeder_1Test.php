<?php

namespace App\Containers\AppSection\Authorization\Tests\Unit\Data\Seeders;

use App\Containers\AppSection\Authorization\Data\Seeders\AuthorizationPermissionsSeeder_1;
use App\Containers\AppSection\Authorization\Tasks\CreatePermissionTask;
use App\Containers\AppSection\User\Tests\UnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;

#[Group('authorization')]
#[CoversClass(AuthorizationPermissionsSeeder_1::class)]
final class AuthorizationPermissionsSeeder_1Test extends UnitTestCase
{
    private array $permissions;

    protected function setUp(): void
    {
        parent::setUp();
        foreach (array_keys(config('auth.guards')) as $guardName) {
            $this->addPermission('manage-roles', 'Create, Update, Delete, Get All, Attach/detach permissions to Roles and Get All Permissions.', guardName: $guardName);
            $this->addPermission('manage-permissions', 'Create, Update, Delete, Get All, Attach/detach permissions to User.', guardName: $guardName);
            $this->addPermission('create-admins', 'Create new Users (Admins) from the dashboard.', guardName: $guardName);
            $this->addPermission('manage-admins-access', 'Assign users to Roles.', guardName: $guardName);
            $this->addPermission('access-dashboard', 'Access the admins dashboard.', guardName: $guardName);
            $this->addPermission('access-private-docs', 'Access the private docs.', guardName: $guardName);
        }
    }

    public function testSeederCallsCorrectTask()
    {
        $taskMock = $this->mock(CreatePermissionTask::class);
        foreach ($this->permissions as $permission) {
            $taskMock->expects()->run(
                $permission['name'],
                $permission['description'],
                $permission['display_name'],
                $permission['guard_name'],
            )->once();
        }

        $this->artisan('db:seed', [
            '--class' => AuthorizationPermissionsSeeder_1::class,
        ])->assertSuccessful();
    }

    public function testDatabaseHasPermissions()
    {
        $tableNames = config('permission.table_names');

        foreach ($this->permissions as $permission) {
            $this->assertDatabaseHas($tableNames['permissions'], $permission);
        }
    }

    private function addPermission(string $name, string|null $description = null, string|null $displayName = null, string $guardName = 'api'): void
    {
        $this->permissions[] = [
            'name' => $name,
            'description' => $description,
            'display_name' => $displayName,
            'guard_name' => $guardName,
        ];
    }
}
