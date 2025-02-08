import {useEffect, useState} from "react";
import Pusher from "pusher-js";
import { useForm } from "@inertiajs/react";
import axios from "axios"

export default function Index({room, user}) {
    const [messages, setMessages] = useState([]);
    const [users, setUsers] = useState(room.users);
    const [diceRoll, setDiceRoll] = useState(null);
    const { post } = useForm();

    useEffect(() => {
        const pusher = new Pusher('53bd861197f35b2ad990', {
            cluster: 'eu',
        });

        const channel = pusher.subscribe(`room-${room.id}`);

        channel.bind("UserJoinedRoomEvent", (data) => {
            console.log("User joined:", data);
            setMessages((prev) => [...prev, data]);
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
        });

        return () => {
            channel.unbind_all();
            channel.unsubscribe();
        };
    }, [room.id]);

    const rollDice = () => {
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
                {room.quest.questions.map((question, index) => (
                    <div
                        key={index}
                        className="w-20 h-20 bg-blue-500 text-white flex items-center justify-center rounded-lg shadow-md cursor-pointer hover:bg-blue-600 relative"
                    >
                        {index + 1}
                        {users.filter(user => user.current_question_id === question.id).map((user, userIndex) => (
                            <div
                                key={userIndex}
                                className="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-xs text-white flex items-center justify-center rounded-full shadow-md"
                            >
                                {user.user.name[0]}
                            </div>
                        ))}
                    </div>
                ))}
            </div>

            <button
                onClick={rollDice}
                className="mt-4 px-4 py-2 bg-green-500 text-white rounded-lg shadow-md hover:bg-green-600"
            >
                Бросить кубик 🎲
            </button>

            {diceRoll !== null && (
                <p className="mt-2 text-lg font-bold">Результат броска: {diceRoll}</p>
            )}

            <ul className="mt-4">
                {messages.map((msg, index) => (
                    <li key={index} className="text-sm text-gray-700">{JSON.stringify(msg)}</li>
                ))}
            </ul>
        </div>
    );
}
