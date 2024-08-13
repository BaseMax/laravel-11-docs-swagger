<?php

namespace App\Actions\User;

use Illuminate\Database\QueryException;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\User;
use App\Jobs\GenerateSiteMapJob;

class CreateUserAction
{
    use AsAction;

    public function handle(array $data): User|null
    {
        $data["role_id"] = 2;

        try {
            $user = User::query()->create($data);

            \DB::commit();

            GenerateSiteMapJob::dispatch();

            return $user;

        } catch (QueryException $exception) {

            \Log::error($exception->getMessage());

            report($exception);

            \DB::rollBack();

            return null;

        }
    }
}
