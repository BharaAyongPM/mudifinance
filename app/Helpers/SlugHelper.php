<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class SlugHelper
{
    public static function unique(string $value, string $modelClass, ?int $ignoreId = null, string $column = 'slug'): string
    {
        $baseSlug = Str::slug($value) ?: 'item';
        $slug = $baseSlug;
        $counter = 2;

        while ($modelClass::query()
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->where($column, $slug)
            ->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
