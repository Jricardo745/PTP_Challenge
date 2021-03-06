<?php

namespace App\Actions\Users;

use App\Entities\User;
use App\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class StoreUsersAction extends Action
{
    public function action(Model $user, array $request): Model
    {
        $user = $user->create($request);
        if (auth()->user()->can('syncRoles', User::class)) {
            $user->syncRoles($request['roles']);
        }

        return $user;
    }
}
