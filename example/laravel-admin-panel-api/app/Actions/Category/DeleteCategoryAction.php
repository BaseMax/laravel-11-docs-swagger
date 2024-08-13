<?php

namespace App\Actions\Category;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Category;
use App\Jobs\GenerateSiteMapJob;

class DeleteCategoryAction
{
    use AsAction;

    public function handle(Category $category)
    {
        try {
            $category->delete();

            \DB::commit();

            GenerateSiteMapJob::dispatch();

            return true;

        } catch (\Exception $e) {

            report($e);

            \DB::rollBack();

            return false;
        }
    }
}
