<?php

namespace App\Traits;

use App\Models\Tag;

trait HasTags
{
    public function tags()
    {
        return $this->morphMany(Tag::class, 'taggable');
    }

    public function addTags($requestTag)
    {
        $tags = explode(',', $requestTag);
        $tagIds = [];
        foreach ($tags as $tagName) {
            if ($tagName) {
                $tag = Tag::firstOrCreate(['name' => trim($tagName)]);
                $tagIds[] = $tag->id;
            }
        }
        return $tagIds;
    }
}
