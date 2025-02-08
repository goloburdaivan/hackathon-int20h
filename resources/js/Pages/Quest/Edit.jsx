import React, { useState } from "react";
import { useForm as useInertiaForm } from "@inertiajs/react";
import {
    Box,
    TextField,
    Button,
    Typography,
    Paper,
    Grid,
    Card,
    CardContent,
    CardActions,
    Avatar,
    IconButton,
} from "@mui/material";
import AddCircleIcon from "@mui/icons-material/AddCircle";
import DeleteIcon from "@mui/icons-material/Delete";
import EditIcon from "@mui/icons-material/Edit";
import UploadFileIcon from "@mui/icons-material/UploadFile";
import AddQuestionDialog from "./AddQuestionDialog";

function EditQuestForm({ quest, question_types }) {
    const { data, setData, patch } = useInertiaForm({
        name: quest.name,
        description: quest.description,
        cover: null,
        questions: quest.questions ?? [],
    });

    const [preview, setPreview] = useState(quest.cover);
    const [openAddQuestion, setOpenAddQuestion] = useState(false);
    const [editingQuestion, setEditingQuestion] = useState(null);

    const handleCoverChange = (e) => {
        const file = e.target.files[0];
        if (file) {
            setData("cover", file);
            setPreview(URL.createObjectURL(file));
        }
    };

    const handleSaveQuest = () => {
        console.log(data);
        patch(`/quests/${quest.id}`);
    };

    const handleDeleteQuestion = (index) => {
        setData("questions", data.questions.filter((_, i) => i !== index));
    };

    const handleAddOrUpdateQuestion = (question) => {
        if (editingQuestion !== null) {
            const updatedQuestions = [...data.questions];
            updatedQuestions[editingQuestion] = question;
            setData("questions", updatedQuestions);
        } else {
            setData("questions", [...data.questions, question]);
        }
        setEditingQuestion(null);
    };

    return (
        <Paper
            sx={{
                padding: "30px",
                maxWidth: "600px",
                margin: "auto",
                boxShadow: 3,
                backgroundColor: "#ffffff",
                borderRadius: "10px",
            }}
        >
            <Typography
                variant="h5"
                sx={{ mb: 2, fontWeight: "bold", textAlign: "center", color: "#388e3c" }}
            >
                Редагування квесту
            </Typography>

            <Box
                component="form"
                onSubmit={(e) => {
                    e.preventDefault();
                    handleSaveQuest();
                }}
                sx={{ display: "flex", flexDirection: "column", gap: 2 }}
            >
                <TextField
                    label="Назва"
                    value={data.name}
                    onChange={(e) => setData("name", e.target.value)}
                    fullWidth
                    sx={{
                        "& .MuiInputLabel-root": { color: "#66bb6a" },
                        "& .MuiOutlinedInput-root": { "& fieldset": { borderColor: "#81c784" } },
                    }}
                />
                <TextField
                    label="Опис"
                    value={data.description}
                    onChange={(e) => setData("description", e.target.value)}
                    fullWidth
                    multiline
                    rows={3}
                    sx={{
                        "& .MuiInputLabel-root": { color: "#66bb6a" },
                        "& .MuiOutlinedInput-root": { "& fieldset": { borderColor: "#81c784" } },
                    }}
                />

                {/* Завантаження зображення (cover) */}
                <Box>
                    <input
                        type="file"
                        accept="image/*"
                        id="quest-cover"
                        style={{ display: "none" }}
                        onChange={handleCoverChange}
                    />
                    <label htmlFor="quest-cover">
                        <Avatar
                            variant="square"
                            sx={{
                                width: "100%",
                                height: 200,
                                cursor: "pointer",
                                borderRadius: "5px",
                            }}
                            src={preview}
                        >
                            <UploadFileIcon sx={{ fontSize: 50 }} />
                        </Avatar>
                    </label>
                </Box>

                {/* Перелік питань */}
                <Typography variant="h6" sx={{ mt: 2, color: "#388e3c" }}>
                    Питання:
                </Typography>
                <Grid container spacing={2}>
                    {data.questions?.map((question, index) => (
                        <Grid item xs={12} key={index}>
                            <Card
                                sx={{
                                    display: "flex",
                                    alignItems: "center",
                                    padding: "10px",
                                    borderRadius: "10px",
                                    boxShadow: 2,
                                }}
                            >
                                <CardContent sx={{ flex: 1 }}>
                                    <Typography variant="subtitle1">{question.title}</Typography>
                                </CardContent>
                                <CardActions>
                                    <IconButton
                                        color="primary"
                                        onClick={() => {
                                            setEditingQuestion(index);
                                            setOpenAddQuestion(true);
                                        }}
                                    >
                                        <EditIcon sx={{color: "#66bb6a"}}/>
                                    </IconButton>
                                    <IconButton color="error" onClick={() => handleDeleteQuestion(index)}>
                                        <DeleteIcon />
                                    </IconButton>
                                </CardActions>
                            </Card>
                        </Grid>
                    ))}
                </Grid>

                {/* Кнопка для відкриття форми додавання питання */}
                <Button
                    startIcon={<AddCircleIcon />}
                    fullWidth
                    variant="outlined"
                    sx={{
                        mt: 2,
                        borderColor: "#66bb6a",
                        color: "#66bb6a",
                        borderRadius: "30px",
                    }}
                    onClick={() => {
                        setEditingQuestion(null);
                        setOpenAddQuestion(true);
                    }}
                >
                    Додати питання
                </Button>

                <Button
                    type="submit"
                    variant="contained"
                    sx={{
                        mt: 2,
                        backgroundColor: "#66bb6a",
                        borderRadius: "30px",
                    }}
                >
                    Зберегти квест
                </Button>
            </Box>

            {/* Діалогове вікно для додавання або редагування питання */}
            <AddQuestionDialog
                open={openAddQuestion}
                onClose={() => setOpenAddQuestion(false)}
                onAdd={handleAddOrUpdateQuestion}
                questionTypes={question_types}
                initialData={editingQuestion !== null ? data.questions[editingQuestion] : null}
            />
        </Paper>
    );
}

export default EditQuestForm;
