<?php

namespace App\Filters\Quest;

use Illuminate\Database\Eloquent\Builder;

class NumberOfQuestionsQueryFilter
{
    public function handle(Builder $builder, $next)
    {
        if (!request()->filled('questions')) {
            return $next($builder);
        }

        $count = request()->input('questions');

        return $next($builder
            ->has('questions', $count)
        );
    }
}
