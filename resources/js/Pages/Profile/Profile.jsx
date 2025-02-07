import { createInertiaApp } from '@inertiajs/react';
import { createRoot } from 'react-dom/client';
import React from 'react';

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.jsx', { eager: true });
        const page = pages[`./Pages/${name}.jsx`];
        return page?.default || page;
    },
    setup({ el, App, props }) {
        createRoot(el).render(<App {...props} />);
    },
});

export default function ProfilePage({ profile }) {
    const { data, setData, post, processing, errors } = useForm({
        avatar: null,
    });
    const [preview, setPreview] = useState(profile.avatar);

    const handleAvatarChange = (e) => {
        const file = e.target.files[0];
        if (file) {
            setData('avatar', file);
            setPreview(URL.createObjectURL(file));
        }
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('profile.update'), {
            onSuccess: () => alert('Profile updated successfully!'),
        });
    };

    return (
        <div className="max-w-lg mx-auto p-4 bg-white shadow rounded-lg">
            <h2 className="text-xl font-bold mb-4">Profile</h2>
            <div className="mb-4">
                {preview && <img src={preview} alt="Avatar" className="w-32 h-32 rounded-full object-cover mx-auto" />}
            </div>
            <form onSubmit={handleSubmit} className="space-y-4">
                <div>
                    <label className="block text-sm font-medium text-gray-700">Avatar</label>
                    <input type="file" onChange={handleAvatarChange} className="mt-1 block w-full" />
                    {errors.avatar && <p className="text-red-500 text-sm">{errors.avatar}</p>}
                </div>
                <button
                    type="submit"
                    className="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 disabled:opacity-50"
                    disabled={processing}
                >
                    {processing ? 'Updating...' : 'Update Profile'}
                </button>
            </form>
        </div>
    );
}
