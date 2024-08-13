<?php

namespace App\Actions\Category;

use Illuminate\Pagination\LengthAwarePaginator;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Category;
use App\Models\Comment;

class GetCategoriesAction
{
    use AsAction;

    public function handle(int $count = 10): LengthAwarePaginator|Comment
    {
        return Category::query()->paginate($count);
    }
}
