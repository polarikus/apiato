<?php

namespace App\Containers\AppSection\User\Tests\Unit\UI\CLI\Commands;

use App\Containers\AppSection\User\Actions\CreateAdminAction;
use App\Containers\AppSection\User\Tests\UnitTestCase;
use App\Containers\AppSection\User\UI\CLI\Commands\CreateAdminCommand;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;

#[Group('user')]
#[CoversClass(CreateAdminCommand::class)]
final class CreateAdminCommandTest extends UnitTestCase
{
    public function testCreateAdmin()
    {
        $name = 'Super Admin';
        $email = 'ganldalf@the.white';
        $password = 'youShallNotPass';

        $actionMock = $this->mock(CreateAdminAction::class);
        $actionMock->expects()->run([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        $this->artisan('apiato:create:admin')
            ->expectsQuestion('Enter the username for this user', $name)
            ->expectsQuestion('Enter the email address of this user', $email)
            ->expectsQuestion('Enter the password for this user', $password)
            ->expectsQuestion('Please confirm the password', $password)
            ->expectsOutput('Admin ' . $email . ' was successfully created')
            ->assertSuccessful();
    }

    public function testCreateAdminWithInvalidPasswordConfirm()
    {
        $name = 'Super Admin';
        $email = 'ganldalf@the.white';
        $password = 'youShallNotPass';

        $this->artisan('apiato:create:admin')
            ->expectsQuestion('Enter the username for this user', $name)
            ->expectsQuestion('Enter the email address of this user', $email)
            ->expectsQuestion('Enter the password for this user', $password)
            ->expectsQuestion('Please confirm the password', 'youShallNotPassed')
            ->expectsOutput('Passwords do not match - exiting!')
            ->assertSuccessful();
    }
}
