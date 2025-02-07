import React from 'react';
import {Link, usePage} from '@inertiajs/react';

export default function Layout({ children }) {
    const { user } = usePage().props;
    return (
        <div className="min-h-screen flex flex-col bg-[#e8f5e9]">
            <header className="bg-gradient-to-r from-green-600 to-green-500 shadow-lg">
                <nav className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    <div className="flex items-center justify-between">
                        <Link href="/" className="text-white text-4xl font-bold tracking-wide hover:text-gray-100 transition duration-300">
                            RzhadQuest
                        </Link>

                        <div className="flex space-x-10 flex-1 justify-center">
                            <Link
                                href="/"
                                className="text-white text-xl font-medium hover:text-gray-100 transition duration-300 hover:bg-white hover:text-green-500 px-4 py-2 rounded-lg"
                            >
                                Головна
                            </Link>
                            <Link
                                href="/quests"
                                className="text-white text-xl font-medium hover:text-gray-100 transition duration-300 hover:bg-white hover:text-green-500 px-4 py-2 rounded-lg"
                            >
                                Квести
                            </Link>
                            <Link
                                href="/leaderboard"
                                className="text-white text-xl font-medium hover:text-gray-100 transition duration-300 hover:bg-white hover:text-green-500 px-4 py-2 rounded-lg"
                            >
                                Список лідерів
                            </Link>
                        </div>

                        <div className="flex items-center">
                            {user ? (
                                <Link
                                    href="/profile"
                                    className="text-white text-xl font-medium hover:text-gray-100 transition duration-300 hover:bg-white hover:text-green-500 px-4 py-2 rounded-lg"
                                >
                                    Профіль
                                </Link>
                            ) : (
                                <Link
                                    href="/login"
                                    className="text-white text-xl font-medium hover:text-gray-100 transition duration-300 hover:bg-white hover:text-green-500 px-4 py-2 rounded-lg"
                                >
                                    Увійти
                                </Link>
                            )}
                        </div>
                    </div>
                </nav>
            </header>

            <main className="flex-1 mx-auto container px-4 sm:px-6 lg:px-8 py-8">
                {children}
            </main>
        </div>
    );
}
