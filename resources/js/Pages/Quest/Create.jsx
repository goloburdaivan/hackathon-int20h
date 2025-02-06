import React, { useState } from 'react';
import { TextField, Button, MenuItem, Select, InputLabel, FormControl, FormHelperText } from '@mui/material';
import { Inertia } from '@inertiajs/inertia';

const CreateQuestion = () => {
    const [questionData, setQuestionData] = useState({
        title: '',
        description: '',
        type: '',
        image: null,
        single_answer: '',
        questions: [],
        coordinates: [],
    });

    const handleChange = (e) => {
        const { name, value } = e.target;
        setQuestionData({ ...questionData, [name]: value });
    };

    const handleImageChange = (e) => {
        setQuestionData({ ...questionData, image: e.target.files[0] });
    };

    const handleAddOption = () => {
        setQuestionData({
            ...questionData,
            questions: [...questionData.questions, { text: '', correct: false }],
        });
    };

    const handleOptionChange = (index, field, value) => {
        const updatedQuestions = [...questionData.questions];
        updatedQuestions[index][field] = value;
        setQuestionData({ ...questionData, questions: updatedQuestions });
    };

    const handleCoordinateClick = (e) => {
        const { offsetX, offsetY } = e.nativeEvent;
        setQuestionData({
            ...questionData,
            coordinates: [...questionData.coordinates, { x: offsetX, y: offsetY }],
        });
    };

    const handleSubmit = async (event) => {
        event.preventDefault();
        console.log(questionData);
    };

    return (
        <div>
            <h2>Создать вопрос</h2>
            <form onSubmit={handleSubmit}>
                <TextField
                    label="Заголовок"
                    name="title"
                    value={questionData.title}
                    onChange={handleChange}
                    fullWidth
                    required
                />
                <TextField
                    label="Описание"
                    name="description"
                    value={questionData.description}
                    onChange={handleChange}
                    fullWidth
                    required
                    multiline
                    rows={4}
                />
                <FormControl fullWidth required>
                    <InputLabel>Тип вопроса</InputLabel>
                    <Select
                        name="type"
                        value={questionData.type}
                        onChange={handleChange}
                    >
                        <MenuItem value="single_answer">Ответ текстом</MenuItem>
                        <MenuItem value="questions">Множественный выбор</MenuItem>
                        <MenuItem value="image">Изображение с координатами</MenuItem>
                    </Select>
                    <FormHelperText>Выберите тип вопроса</FormHelperText>
                </FormControl>

                {questionData.type === 'single_answer' && (
                    <TextField
                        label="Правильный ответ"
                        name="single_answer"
                        value={questionData.single_answer}
                        onChange={handleChange}
                        fullWidth
                        required
                    />
                )}

                {questionData.type === 'questions' && (
                    <div>
                        {questionData.questions.map((option, index) => (
                            <div key={index}>
                                <TextField
                                    label="Текст ответа"
                                    value={option.text}
                                    onChange={(e) =>
                                        handleOptionChange(index, 'text', e.target.value)
                                    }
                                    fullWidth
                                />
                                <FormControl fullWidth>
                                    <InputLabel>Правильный?</InputLabel>
                                    <Select
                                        value={option.correct ? 'yes' : 'no'}
                                        onChange={(e) =>
                                            handleOptionChange(index, 'correct', e.target.value === 'yes')
                                        }
                                    >
                                        <MenuItem value="yes">Да</MenuItem>
                                        <MenuItem value="no">Нет</MenuItem>
                                    </Select>
                                </FormControl>
                            </div>
                        ))}
                        <Button onClick={handleAddOption}>Добавить вариант ответа</Button>
                    </div>
                )}

                {questionData.type === 'image' && (
                    <div>
                        <input type="file" accept="image/*" onChange={handleImageChange} />
                        {questionData.image && (
                            <div>
                                <img
                                    src={URL.createObjectURL(questionData.image)}
                                    alt="Uploaded"
                                    width="300"
                                    height="auto"
                                    style={{ cursor: 'pointer' }}
                                    onClick={handleCoordinateClick}
                                />
                                <p>Нажмите на изображение, чтобы выбрать область</p>
                            </div>
                        )}
                        <div>
                            <h4>Координаты:</h4>
                            <pre>{JSON.stringify(questionData.coordinates, null, 2)}</pre>
                        </div>
                    </div>
                )}

                <Button type="submit" variant="contained">Сохранить вопрос</Button>
            </form>
        </div>
    );
};

export default CreateQuestion;
