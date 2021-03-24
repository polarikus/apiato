<?php

namespace App\Containers\User\Actions;

use Apiato\Core\Foundation\Facades\Apiato;
use App\Containers\User\Models\User;
use App\Containers\User\UI\API\Requests\UpdateUserRequest;
use App\Ship\Parents\Actions\Action;

class UpdateUserAction extends Action
{
    public function run(UpdateUserRequest $request): User
    {
        $sanitizedData = $request->sanitizeInput([
            'password',
            'name',
            'gender',
            'birth',
            'social_token',
            'social_expires_in',
            'social_refresh_token',
            'social_token_secret'
        ]);

        return Apiato::call('User@UpdateUserTask', [$sanitizedData, $request->id]);
    }
}
