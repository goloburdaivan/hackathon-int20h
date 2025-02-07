<?php

namespace App\Enums;

enum QuestionType: string
{
    case SINGLE_ANSWER = 'single_answer';
    case QUESTIONS = 'questions';
    case IMAGE = 'image';

    public static function getLists(): array
    {
        return [
            self::SINGLE_ANSWER->value => 'Single answer',
            self::QUESTIONS->value => 'Questions',
            self::IMAGE->value => 'Image',
        ];
    }
}
