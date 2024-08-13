<?php

namespace App\Actions\Category;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Jobs\GenerateSiteMapJob;
use App\Models\Category;

class UpdateCategoryAction
{
    use AsAction;

    public function handle(Category $category, array $data)
    {
        try {

            $category->update($data);

            GenerateSiteMapJob::dispatch();

            $category->save();

            return $category;

        } catch (\Exception $e) {

            report($e);

            return null;

        }
    }
}
