import React, { useState, useEffect } from 'react';
import { Link, router, usePage } from '@inertiajs/react';

export default function QuestList({ quests }) {
    const { props } = usePage();
    const [search, setSearch] = useState(props.q || '');
    const [questions, setQuestions] = useState(props.questions || '');

    const handleFilterChange = () => {
        router.get('quests', {
            q: search,
            questions: questions ? Number(questions) : ''
        }, { preserveState: true, replace: true });
    };

    const handleClearFilters = () => {
        setSearch('');
        setQuestions('');
        router.get('quests', {}, { preserveState: true, replace: true });
    };

    useEffect(() => {
        setSearch(new URLSearchParams(window.location.search).get('q') || '');
        setQuestions(new URLSearchParams(window.location.search).get('questions') || '');
    }, [window.location.search]);

    return (
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 className="text-4xl font-bold text-gray-900 mb-8">Список квестів</h1>

            <div className="mb-6 flex flex-col sm:flex-row justify-center gap-4">
                <input
                    type="text"
                    value={search}
                    onChange={(e) => setSearch(e.target.value)}
                    placeholder="Пошук квестів..."
                    className="border border-gray-300 rounded-lg px-4 py-2"
                />
                <input
                    type="number"
                    value={questions}
                    onChange={(e) => {
                        const value = e.target.value;
                        if (value === '' || (Number(value) >= 0 && Number.isInteger(Number(value)))) {
                            setQuestions(value);
                        }
                    }}
                    onKeyDown={(e) => {
                        if (e.key === 'ArrowUp' || e.key === 'ArrowDown') {
                            e.preventDefault();
                        }
                    }}
                    placeholder="Кількість питань"
                    className="border border-gray-300 rounded-lg px-4 py-2"
                />
                <button
                    onClick={handleFilterChange}
                    className="bg-[#388e3c] text-white px-6 py-2 rounded-lg hover:bg-green-600 transition font-semibold"
                >
                    Застосувати
                </button>
                <button
                    onClick={handleClearFilters}
                    className="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition font-semibold"
                >
                    Очистити фільтри
                </button>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 justify-center">
                {quests.data.length > 0 ? (
                    quests.data.map(quest => (
                        <div
                            key={quest.id}
                            className="bg-white shadow-xl rounded-2xl overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-2xl"
                        >
                            <img
                                src={quest.cover || 'https://via.placeholder.com/400x300'}
                                alt={quest.name}
                                className="w-full h-52 object-cover"
                            />
                            <div className="p-6">
                                <h2 className="text-2xl font-semibold text-gray-800">{quest.name}</h2>
                                <p className="text-gray-600 mt-2">{quest.description?.substring(0, 100)}...</p>
                                <p className="text-gray-700 mt-2 font-semibold">Кількість питань: {quest.questions_count}</p>
                                <div className="mt-4 flex justify-center">
                                    <Link
                                        href={`/quests/${quest.id}`}
                                        className="bg-[#388e3c] text-white py-2 px-6 rounded-lg hover:bg-green-600 transition font-semibold"
                                    >
                                        Пройти квест
                                    </Link>
                                </div>
                            </div>
                        </div>
                    ))
                ) : (
                    <p className="text-gray-600 col-span-full text-center text-xl">Немає доступних квестів.</p>
                )}
            </div>

            {quests.meta && quests.meta.links && quests.meta.links.length > 1 && (
                <div className="mt-8 flex justify-center space-x-2">
                    {quests.meta.links.map((link, index) => (
                        link.url ? (
                            <Link
                                key={index}
                                href={link.url}
                                className={`px-4 py-2 border rounded-lg ${link.active ? "bg-[#388e3c] text-white" : "bg-white text-gray-600 hover:bg-gray-100"}`}
                                dangerouslySetInnerHTML={{ __html: link.label }}
                            />
                        ) : (
                            <span
                                key={index}
                                className="px-4 py-2 border rounded-lg bg-gray-200 text-gray-500 cursor-not-allowed"
                                dangerouslySetInnerHTML={{ __html: link.label }}
                            />
                        )
                    ))}
                </div>
            )}
        </div>
    );
}
