<?php

namespace App\Enums;

enum QuestionType: string
{
    case SINGLE_ANSWER = 'single_answer';
    case QUESTIONS = 'questions';
    case IMAGE = 'image';
}
