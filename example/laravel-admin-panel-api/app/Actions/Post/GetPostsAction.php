<?php

namespace App\Actions\Post;

use Illuminate\Pagination\LengthAwarePaginator;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Post;

class GetPostsAction
{
    use AsAction;

    public function handle(int $count = 10): LengthAwarePaginator|Post
    {
        return Post::query()->paginate($count);
    }
}
