<?php

namespace App\Actions\Comment;

use Illuminate\Pagination\LengthAwarePaginator;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Comment;

class GetCommentsAction
{
    use AsAction;

    public function handle(int $count=10): LengthAwarePaginator|Comment
    {
        return Comment::paginate($count);
    }
}
