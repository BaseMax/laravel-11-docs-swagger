<?php

namespace App\Actions\Comment;

use Illuminate\Database\QueryException;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Comment;
use App\Jobs\GenerateSiteMapJob;

class CreateCommentAction
{
    use AsAction;

    public function handle(array $data): Comment|null
    {
        try {

            $comment = Comment::query()->create($data);

            \DB::commit();

            GenerateSiteMapJob::dispatch();

            return $comment;

        } catch (QueryException $exception) {

            \Log::error($exception->getMessage());

            report($exception);

            \DB::rollBack();

            return null;

        }
    }
}
