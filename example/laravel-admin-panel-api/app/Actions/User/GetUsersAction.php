<?php

namespace App\Actions\User;

use Illuminate\Pagination\LengthAwarePaginator;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\User;

class GetUsersAction
{
    use AsAction;

    public function handle(int $count = 10): LengthAwarePaginator|User
    {
        return User::query()->paginate($count);
    }
}
