<?php

namespace App\Actions\Post;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Post;
use App\Jobs\GenerateSiteMapJob;

class DeletePostAction
{
    use AsAction;

    public function handle(Post $post): bool
    {
        try {
            $post->delete();

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
