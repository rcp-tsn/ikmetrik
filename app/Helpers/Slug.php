<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;

trait Slug
{
    /**
     * @param $title
     * @param int $id
     * @return string
     * @throws \Exception
     */
    public function createSlug($title, Model $model, $id = 0)
    {
        // Normalize the title
        $slug = str_slug($title);

        $allSlugs = $this->getRelatedSlugs($slug, $model, $id);

        // If we haven't used it before then we are all good.
        if (! $allSlugs->contains('slug', $slug)){
            return $slug;
        }
        // Just append numbers like a savage until we find not used.
        for ($i = 1; $i <= 10; $i++) {
            $newSlug = $slug.'-'.$i;
            if (! $allSlugs->contains('slug', $newSlug)) {
                return $newSlug;
            }
        }
        throw new \Exception('Can not create a unique slug');
    }

    /**
     * @param $slug
     * @param Model $model
     * @param int $id
     *
     * @return mixed
     */
    protected function getRelatedSlugs($slug, Model $model, $id = 0)
    {
        return $model::select('slug')->where('slug', 'like', $slug.'%')
                   ->where('id', '<>', $id)
                   ->get();
    }
}