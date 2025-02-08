import { useEffect, useState } from "react";
import Pusher from "pusher-js";
import { useForm } from "@inertiajs/react";
import axios from "axios";

export default function Index({ room, user }) {
    const [messages, setMessages] = useState([]);
    const [users, setUsers] = useState(room.users);
    const [diceRoll, setDiceRoll] = useState(null);
    const [newUser, setNewUser] = useState(null);
    const [gameStatus, setGameStatus] = useState(room.status);
    const [currentQuestion, setCurrentQuestion] = useState(null);
    const [userAnswer, setUserAnswer] = useState("");
    const [selectedOption, setSelectedOption] = useState(false);
    const [clickCoordinates, setClickCoordinates] = useState(null);
    const [isCorrect, setIsCorrect] = useState(null);
    const [isDiceBlocked, setIsDiceBlocked] = useState(false); // 🔥 Блокировка кубика
    const [timer, setTimer] = useState(0); // 🔥 Таймер 30 секунд
    const { post } = useForm();

    useEffect(() => {
        const pusher = new Pusher('53bd861197f35b2ad990', {
            cluster: 'eu',
        });

        const channel = pusher.subscribe(`room-${room.id}`);

        channel.bind("UserJoinedRoomEvent", (data) => {
            console.log("User joined:", data);
            setUsers((prevUsers) => [...prevUsers, { user: data.user, current_question_id: room.quest.questions[0].id }]);
            setNewUser(data.user.name);
        });

        channel.bind("UserMovedToQuestionEvent", (data) => {
            setUsers((prevUsers) => {
                const updatedUsers = prevUsers.map((element) =>
                    element.user.id === data.user.id
                        ? { ...element, current_question_id: data.question_id }
                        : element
                );

                console.log("Updated users inside setUsers:", updatedUsers);
                return updatedUsers;
            });

            if (data.user.id === user.id) {
                fetchQuestion(data.question_id);
            }
        });

        channel.bind("GameStartedEvent", () => {
            console.log("Game has started!");
            setGameStatus("IN_PROGRESS");
        });

        return () => {
            channel.unbind_all();
            channel.unsubscribe();
        };
    }, [room.id]);

    useEffect(() => {
        if (timer > 0) {
            const interval = setInterval(() => {
                setTimer((prev) => prev - 1);
            }, 1000);

            return () => clearInterval(interval);
        } else {
            setIsDiceBlocked(false);
        }
    }, [timer]);


    const fetchQuestion = (questionId) => {
        axios.get(`/quests/${room.quest.id}/questions/${questionId}`)
            .then(response => {
                setCurrentQuestion(response.data.question);
            })
            .catch(error => {
                console.error("Error fetching question:", error);
            });
    };

    const handleImageClick = (event) => {
        const imgRect = event.target.getBoundingClientRect();
        const x = event.clientX - imgRect.left;
        const y = event.clientY - imgRect.top;
        setClickCoordinates([x, y]);
    };

    const checkAnswer = () => {
        let payload = {};

        if (currentQuestion.type === "single_answer") {
            payload = { room_id: room.id, single_answer: userAnswer };
        } else if (currentQuestion.type === "questions") {
            payload = { room_id: room.id, question: selectedOption };
        } else if (currentQuestion.type === "image") {
            payload = { room_id: room.id, coordinates: clickCoordinates };
        }

        axios.post(`/quests/${room.quest.id}/questions/${currentQuestion.id}/check`, payload)
            .then(response => {
                setIsCorrect(response.data.correctness);
                if (!response.data.correctness) {
                    setIsDiceBlocked(true);
                    setTimer(30);
                }
            })
            .catch(error => {
                console.error("Error checking answer:", error);
            });
    };

    const rollDice = () => {
        if (isDiceBlocked) return;
        const result = Math.floor(Math.random() * 6) + 1;
        setDiceRoll(result);

        const currentUser = users.find(element => element.user.id === user.id);
        if (currentUser) {
            const currentQuestionIndex = room.quest.questions.findIndex(q => q.id === currentUser.current_question_id);
            const newIndex = Math.min(currentQuestionIndex + result, room.quest.questions.length - 1);
            const newQuestionId = room.quest.questions[newIndex].id;
            axios.post(`/rooms/${room.id}/roll-dice`, { question_id: newQuestionId })
                .then(response => {
                    console.log("Response:", response.data);
                })
                .catch(error => {
                    console.error("Error rolling dice:", error);
                });
        }
    };

    return (
        <div className="p-4">
            <h1 className="text-xl font-bold mb-4">Комната #{room.id}</h1>

            <div className="grid grid-cols-4 gap-4 relative">
                {room.quest.questions.map((question, index) => {
                    const usersOnQuestion = users.filter(user => user.current_question_id === question.id);

                    return (
                        <div
                            key={index}
                            className="w-20 h-20 bg-blue-500 text-white flex items-center justify-center rounded-lg shadow-md cursor-pointer hover:bg-blue-600 relative"
                        >
                            {index + 1}

                            {usersOnQuestion.length > 0 && (
                                <div className="absolute bottom-0 left-0 flex flex-wrap w-full justify-center space-x-[-5px] space-y-[-5px]">
                                    {usersOnQuestion.map((user, userIndex) => (
                                        <div
                                            key={userIndex}
                                            className="w-6 h-6 bg-red-500 text-xs text-white flex items-center justify-center rounded-full shadow-md"
                                            style={{ zIndex: userIndex, transform: `translateY(${userIndex * 5}px)` }}
                                        >
                                            {user.user.name[0]}
                                        </div>
                                    ))}
                                </div>
                            )}
                        </div>
                    );
                })}
            </div>

            {gameStatus === "IN_PROGRESS" ? (
                <button
                    onClick={rollDice}
                    className="mt-4 px-4 py-2 bg-green-500 text-white rounded-lg shadow-md hover:bg-green-600"
                >
                    {isDiceBlocked ? `Подождите ${timer} сек` : "Бросить кубик 🎲"}
                </button>
            ) : (
                <p className="mt-4 text-gray-500">Ожидание начала игры...</p>
            )}

            {diceRoll !== null && (
                <p className="mt-2 text-lg font-bold">Результат броска: {diceRoll}</p>
            )}

            {currentQuestion && (
                <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                    <div className="bg-white p-6 rounded-lg shadow-lg text-center max-w-lg">
                        <h2 className="text-lg font-bold">{currentQuestion.title}</h2>
                        <p className="mt-2">{currentQuestion.description}</p>

                        {currentQuestion.type === "single_answer" && (
                            <input
                                type="text"
                                placeholder="Введите ответ"
                                value={userAnswer}
                                onChange={(e) => setUserAnswer(e.target.value)}
                                className="border p-2 mt-4 w-full"
                            />
                        )}

                        {currentQuestion.type === "questions" && (
                            <div className="mt-4">
                                {currentQuestion.questions.map((q, index) => (
                                    <div key={index} className="flex items-center space-x-2">
                                        <input
                                            type="radio"
                                            name="answer"
                                            value={q.correct}
                                            onChange={(event) => setSelectedOption(event.target.value === "true")}
                                        />
                                        <label className="cursor-pointer">{q.text}</label>
                                    </div>
                                ))}
                            </div>
                        )}

                        {currentQuestion.type === "image" && (
                            <div className="mt-4 relative">
                                <img
                                    src={currentQuestion.image}
                                    alt="Выберите область"
                                    className="w-full h-auto cursor-pointer"
                                    onClick={handleImageClick}
                                />
                            </div>
                        )}

                        <button
                            onClick={checkAnswer}
                            className="mt-4 px-4 py-2 bg-green-500 text-white rounded-lg shadow-md hover:bg-green-600"
                        >
                            Ответить
                        </button>
                        <button
                            onClick={() => setCurrentQuestion(null)}
                            className="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600"
                        >
                            Закрыть
                        </button>
                    </div>
                </div>
            )}

            {newUser && (
                <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                    <div className="bg-white p-6 rounded-lg shadow-lg text-center">
                        <h2 className="text-lg font-bold">Новый игрок присоединился!</h2>
                        <p className="text-xl">{newUser}</p>
                        <button
                            onClick={() => setNewUser(null)}
                            className="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600"
                        >
                            OK
                        </button>
                    </div>
                </div>
            )}
        </div>
    );
}
