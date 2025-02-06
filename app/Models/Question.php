<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $title
 * @property string $description
 * @property string $type
 * @property string $image
 * @property string $single_answer
 * @property array $questions
 * @property array $coordinates
 */
class Question extends Model
{
    protected $fillable = [
        'title',
        'description',
        'type',
        'image',
        'single_answer',
        'questions',
        'coordinates',
    ];

    protected $casts = [
        'questions' => 'array',
        'coordinates' => 'array',
    ];
}
