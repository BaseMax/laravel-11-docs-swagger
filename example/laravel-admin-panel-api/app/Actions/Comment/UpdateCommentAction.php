<?php

namespace App\Actions\Comment;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Jobs\GenerateSiteMapJob;
use App\Models\Comment;

class UpdateCommentAction
{
    use AsAction;

    public function handle(Comment $comment, array $data)
    {
        try {

            $comment->update($data);

            GenerateSiteMapJob::dispatch();

            $comment->save();

            return $comment;

        } catch (\Exception $e) {

            report($e);

            return null;

        }
    }
}
