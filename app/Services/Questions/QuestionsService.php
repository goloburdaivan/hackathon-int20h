<?php

namespace App\Services\Questions;

use App\Enums\QuestionType;
use App\Enums\RoomStatusEnum;
use App\Events\Room\GameCompletedEvent;
use App\Http\Requests\Questions\CheckAnswerRequest;
use App\Http\Requests\Questions\CreateQuestionRequest;
use App\Http\Requests\Questions\UpdateQuestionRequest;
use App\Models\Quest;
use App\Models\Question;
use App\Models\Room;
use App\Models\User;
use App\Repository\QuestionRepository;
use App\Repository\RoomRepository;
use Exception;
use Illuminate\Support\Str;

class QuestionsService
{
    public function __construct(
        private readonly QuestionRepository $questionRepository,
        private readonly RoomRepository $roomRepository,
    )
    {
    }

    /**
     * @throws Exception
     */
    public function create(Quest $quest, CreateQuestionRequest $request): Question
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('cover')->store('quests', 'public');
        }

        /** @var Question */
        return $this->questionRepository->create($data + [
                'quest_id' => $quest->id,
            ]);
    }

    /**
     * @throws Exception
     */
    public function update(Question $question, UpdateQuestionRequest $request): Question
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('cover')->store('quests', 'public');
        }

        /** @var Question */
        return $this->questionRepository->update($question, $data);
    }

    public function findByQuest(int $questId): Question
    {
        return $this->questionRepository->query()->where('quest_id', $questId)->firstOrFail();
    }

    public function delete(Question $question): void
    {
        $this->questionRepository->delete($question->id);
    }

    public function findById(int $id): ?Question
    {
        /** @var Question */
        return $this->questionRepository->find($id);
    }

    /**
     * @throws Exception
     */
    public function checkCorrectness(Question $question, CheckAnswerRequest $request): bool
    {
        $data = $request->validated();

        $result =  match ($question->type) {
            QuestionType::SINGLE_ANSWER => $this->checkSingleAnswer($question, $data['single_answer']),
            QuestionType::QUESTIONS => $data['question'],
            QuestionType::IMAGE => $this->checkCoordinates($question, $data['coordinates']),
        };

        if ($result) {
            /** @var User $user */
            $user = auth()->user();
            /** @var Room $room */
            $room = $this->roomRepository->find($data['room_id']);
            $participant = $room->participants()->where('user_id', $user->id)->first();
            $lastQuestion = $room->quest->questions()->orderBy('id')->get()->last();

            if ($lastQuestion->id === $participant->question_id) {
                $this->roomRepository->update($room, [
                    'status' => RoomStatusEnum::COMPLETED,
                ]);
                event(new GameCompletedEvent($room->id));
            }
        }

        return $result;
    }

    private function checkSingleAnswer(Question $question, string $answer): bool
    {
        return Str::lower($question->single_answer) === Str::lower($answer);
    }

    private function checkCoordinates(Question $question, array $coordinates): bool
    {
        [$x1, $y1, $x2, $y2] = $question->coordinates;

        [$x, $y] = $coordinates;

        return $x >= $x1 && $x <= $x2 && $y >= $y1 && $y <= $y2;
    }
}
