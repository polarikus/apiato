<?php

namespace App\Containers\AppSection\User\Tests\Unit\Data\Seeders;

use App\Containers\AppSection\Authorization\Tasks\CreatePermissionTask;
use App\Containers\AppSection\User\Data\Seeders\UserPermissionsSeeder_1;
use App\Containers\AppSection\User\Tests\UnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;

#[Group('user')]
#[CoversClass(UserPermissionsSeeder_1::class)]
final class UserPermissionsSeeder_1Test extends UnitTestCase
{
    private array $permissions;

    protected function setUp(): void
    {
        parent::setUp();
        foreach (array_keys(config('auth.guards')) as $guardName) {
            $this->addPermission('search-users', 'Find a User in the DB.', guardName: $guardName);
            $this->addPermission('list-users', 'Get All Users.', guardName: $guardName);
            $this->addPermission('update-users', 'Update a User.', guardName: $guardName);
            $this->addPermission('delete-users', 'Delete a User.', guardName: $guardName);
            $this->addPermission('refresh-users', 'Refresh User data.', guardName: $guardName);
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
            '--class' => UserPermissionsSeeder_1::class,
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
