<?php

namespace App\Actions\Comment;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Comment;

class DeleteCommentAction
{
    use AsAction;

    public function handle(Comment $comment)
    {
        try {
            $comment->delete();

            \DB::commit();

            return true;

        } catch (\Exception $e) {

            report($e);

            \DB::rollBack();

            return false;
        }
    }
}
