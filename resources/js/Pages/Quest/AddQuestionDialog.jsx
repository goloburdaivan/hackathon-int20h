import React, { useState, useEffect } from "react";
import {
    Box,
    TextField,
    Button,
    Typography,
    Dialog,
    DialogTitle,
    DialogContent,
    DialogActions,
    FormControl,
    InputLabel,
    Select,
    MenuItem,
    IconButton,
} from "@mui/material";
import AddCircleIcon from "@mui/icons-material/AddCircle";
import DeleteIcon from "@mui/icons-material/Delete";
import SaveIcon from "@mui/icons-material/Save";
import CloseIcon from "@mui/icons-material/Close";

function AddQuestionDialog({ open, onClose, onAdd, questionTypes, initialData }) {
    const [questionData, setQuestionData] = useState({
        title: "",
        description: "",
        type: Object.keys(questionTypes)[0],
        options: [],
        answer: "",
        coordinates: [],
    });

    // Заповнюємо форму, якщо ми редагуємо питання
    useEffect(() => {
        if (initialData) {
            setQuestionData(initialData);
        } else {
            setQuestionData({
                title: "",
                description: "",
                type: Object.keys(questionTypes)[0],
                options: [],
                answer: "",
                coordinates: [],
            });
        }
    }, [initialData]);

    const handleAddOption = () => {
        setQuestionData({
            ...questionData,
            options: [...questionData.options, { text: "", correct: false }],
        });
    };

    const handleOptionChange = (index, field, value) => {
        const updatedOptions = [...questionData.options];
        updatedOptions[index][field] = value;
        setQuestionData({ ...questionData, options: updatedOptions });
    };

    const handleDeleteOption = (index) => {
        setQuestionData({
            ...questionData,
            options: questionData.options.filter((_, i) => i !== index),
        });
    };

    const handleSave = () => {
        onAdd(questionData);
        onClose();
    };

    return (
        <Dialog open={open} onClose={onClose} maxWidth="sm" fullWidth>
            <DialogTitle>{initialData ? "Редагувати питання" : "Додати питання"}</DialogTitle>
            <DialogContent>
                <TextField
                    label="Назва питання"
                    fullWidth
                    margin="normal"
                    value={questionData.title}
                    onChange={(e) => setQuestionData({ ...questionData, title: e.target.value })}
                />
                <TextField
                    label="Опис"
                    fullWidth
                    margin="normal"
                    multiline
                    rows={3}
                    value={questionData.description}
                    onChange={(e) => setQuestionData({ ...questionData, description: e.target.value })}
                />
                <FormControl fullWidth margin="normal">
                    <InputLabel>Тип питання</InputLabel>
                    <Select
                        value={questionData.type}
                        onChange={(e) => setQuestionData({ ...questionData, type: e.target.value })}
                    >
                        {Object.entries(questionTypes).map(([key, label]) => (
                            <MenuItem key={key} value={key}>
                                {label}
                            </MenuItem>
                        ))}
                    </Select>
                </FormControl>

                {questionData.type === "single_answer" && (
                    <TextField
                        label="Правильна відповідь"
                        fullWidth
                        margin="normal"
                        value={questionData.answer}
                        onChange={(e) => setQuestionData({ ...questionData, answer: e.target.value })}
                    />
                )}

                {questionData.type === "questions" && (
                    <Box>
                        {questionData.options.map((option, index) => (
                            <Box key={index} sx={{ display: "flex", alignItems: "center", gap: "10px", mb: 1 }}>
                                <TextField
                                    label={`Варіант ${index + 1}`}
                                    fullWidth
                                    value={option.text}
                                    onChange={(e) => handleOptionChange(index, "text", e.target.value)}
                                />
                                <IconButton color="error" onClick={() => handleDeleteOption(index)}>
                                    <DeleteIcon />
                                </IconButton>
                            </Box>
                        ))}
                        <Button startIcon={<AddCircleIcon />} onClick={handleAddOption} sx={{ mt: 2 }}>
                            Додати варіант
                        </Button>
                    </Box>
                )}
            </DialogContent>
            <DialogActions>
                <Button onClick={onClose} variant="outlined" startIcon={<CloseIcon />}>
                    Скасувати
                </Button>
                <Button onClick={handleSave} variant="contained" startIcon={<SaveIcon />}>
                    Зберегти
                </Button>
            </DialogActions>
        </Dialog>
    );
}

export default AddQuestionDialog;
