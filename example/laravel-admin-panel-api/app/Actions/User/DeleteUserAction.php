<?php

namespace App\Actions\User;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\User;
use App\Jobs\GenerateSiteMapJob;

class DeleteUserAction
{
    use AsAction;

    public function handle(User $user): bool
    {
        try {
            $user->delete();

            \DB::commit();

            GenerateSiteMapJob::dispatch();

            return true;

        } catch (\Exception $e) {

            report($e);

            \DB::rollBack();

            return false;
        }
    }
}
