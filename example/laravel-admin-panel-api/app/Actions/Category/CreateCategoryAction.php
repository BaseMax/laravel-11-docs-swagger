<?php

namespace App\Actions\Category;

use Illuminate\Database\QueryException;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Jobs\GenerateSiteMapJob;
use App\Models\Category;

class CreateCategoryAction
{
    use AsAction;

    public function handle(array $data): Category|null
    {
        try {

            $category = Category::query()->create($data);

            \DB::commit();

            GenerateSiteMapJob::dispatch();

            return $category;

        } catch (QueryException $exception) {

            \Log::error($exception->getMessage());

            report($exception);

            \DB::rollBack();

            return null;

        }
    }
}
