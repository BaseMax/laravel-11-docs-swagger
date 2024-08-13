<?php

namespace App\Actions\User;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\User;
use App\Jobs\GenerateSiteMapJob;

class UpdateUserAction
{
    use AsAction;

    public function handle(User $user, array $data): User|null
    {
        try {
            $user->update($data);

            GenerateSiteMapJob::dispatch();

            $user->save();

            return $user;
        } catch (\Exception $e) {
            report($e);

            return null;
        }
    }
}
