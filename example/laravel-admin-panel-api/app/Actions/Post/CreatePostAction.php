<?php

namespace App\Actions\Post;

use Illuminate\Database\QueryException;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Post;
use App\Jobs\GenerateSiteMapJob;

class CreatePostAction
{
    use AsAction;

    public function handle(array $data): Post|null
    {
        try {

            $post = Post::query()->create($data);

            \DB::commit();

            GenerateSiteMapJob::dispatch();

            return $post;

        } catch (QueryException $exception) {

            \Log::error($exception->getMessage());

            report($exception);

            \DB::rollBack();

            return null;

        }
    }
}
