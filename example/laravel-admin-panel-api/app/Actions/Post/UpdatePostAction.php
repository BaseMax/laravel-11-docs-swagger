<?php

namespace App\Actions\Post;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Post;
use App\Jobs\GenerateSiteMapJob;

class UpdatePostAction
{
    use AsAction;

    public function handle(Post $post, array $data): Post|null
    {
        try {

            $post->update($data);

            GenerateSiteMapJob::dispatch();

            $post->save();

            return $post;

        } catch (\Exception $e) {

            report($e);

            return null;

        }
    }
}
