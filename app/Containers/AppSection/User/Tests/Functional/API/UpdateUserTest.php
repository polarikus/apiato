<?php

namespace App\Containers\AppSection\User\Tests\Functional\API;

use App\Containers\AppSection\User\Gender;
use App\Containers\AppSection\User\Tests\Functional\ApiTestCase;
use Illuminate\Support\Carbon;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\Group;

#[Group('user')]
#[CoversNothing]
final class UpdateUserTest extends ApiTestCase
{
    protected string $endpoint = 'patch@v1/users/{id}';

    protected array $access = [
        'permissions' => 'update-users',
        'roles' => null,
    ];

    public function testUpdateExistingUser(): void
    {
        $user = $this->getTestingUser([
            'name' => 'He who should not be named',
            'gender' => Gender::FEMALE,
        ]);
        $data = [
            'name' => 'Updated Name',
            'gender' => Gender::MALE->value,
            'birth' => Carbon::today(),
        ];

        $response = $this->injectId($user->id)->makeCall($data);

        $response->assertOk();
        $response->assertJson(
            fn (AssertableJson $json) => $json->has('data')
                ->where('data.object', 'User')
                ->where('data.email', $user->email)
                ->where('data.name', $data['name'])
                ->where('data.gender', $data['gender'])
                ->where('data.birth', Carbon::parse($data['birth'])->toISOString())
                ->missing('data.password')
                ->etc(),
        );
    }

    public function testUpdateNonExistingUser(): void
    {
        $invalidId = 7777777;

        $response = $this->injectId($invalidId)->makeCall();

        $response->assertNotFound();
    }

    public function testUpdateExistingUserWithEmptyValues(): void
    {
        $user = $this->getTestingUser();
        $data = [
            'name' => '',
            'gender' => '',
            'birth' => '',
        ];

        $response = $this->injectId($user->id)->makeCall($data);

        $response->assertUnprocessable();
        $response->assertJson(
            fn (AssertableJson $json) => $json->has('errors')
                ->where('errors.name.0', 'The name field must be at least 2 characters.')
                ->where('errors.gender.0', 'The selected gender is invalid.')
                ->where('errors.birth.0', 'The birth field must be a valid date.')
                ->etc(),
        );
    }
}
