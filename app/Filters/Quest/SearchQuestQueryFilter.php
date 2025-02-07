<?php

namespace App\Filters\Quest;

use Illuminate\Database\Eloquent\Builder;

class SearchQuestQueryFilter
{
    public function handle(Builder $builder, $next)
    {
        if (!request()->filled('q')) {
            return $next($builder);
        }

        $query = request()->input('q');

        return $next($builder
            ->where('name', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
        );
    }
}
