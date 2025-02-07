import React, { useState } from "react";
import { useForm } from "@inertiajs/react";
import {
    Box,
    Typography,
    Container,
    CssBaseline,
    Avatar,
    Button,
    TextField,
} from "@mui/material";
import AddPhotoAlternateIcon from "@mui/icons-material/AddPhotoAlternate";
import SaveIcon from "@mui/icons-material/Save";
import CloseIcon from "@mui/icons-material/Close";

function CreateQuest() {
    const { data, setData, post, progress } = useForm({
        name: "",
        description: "",
        cover: null,
    });

    const [preview, setPreview] = useState(null);

    const handleSubmit = (e) => {
        e.preventDefault();
        post("/quests");
    };

    const handleCoverChange = (e) => {
        const file = e.target.files[0];
        if (file) {
            setData("cover", file);
            setPreview(URL.createObjectURL(file));
        }
    };

    return (
        <Box sx={{ minHeight: "100vh", backgroundColor: "#e8f5e9", py: 5 }}>
            <CssBaseline />
            <Container component="main" maxWidth="md">
                <Box
                    sx={{
                        display: "flex",
                        flexDirection: "column",
                        alignItems: "center",
                        backgroundColor: "#ffffff",
                        padding: "50px",
                        borderRadius: "10px",
                        boxShadow: 5,
                        maxWidth: "600px",
                        margin: "auto",
                        position: "relative",
                    }}
                >
                    <Typography variant="h4" sx={{ fontWeight: "bold", color: "#388e3c", mb: 2 }}>
                        Створення квесту
                    </Typography>

                    <input type="file" accept="image/*" id="cover-upload" style={{ display: "none" }} onChange={handleCoverChange} />
                    <label htmlFor="cover-upload">
                        <Avatar
                            sx={{
                                width: 120,
                                height: 120,
                                bgcolor: "#81c784",
                                margin: "auto",
                                mb: 2,
                                cursor: "pointer",
                            }}
                            src={preview}
                        >
                            {!preview && <AddPhotoAlternateIcon sx={{ fontSize: 50 }} />}
                        </Avatar>
                    </label>

                    {progress && <Typography variant="body2">Завантаження: {progress.percentage}%</Typography>}

                    <TextField
                        label="Назва квесту"
                        variant="outlined"
                        fullWidth
                        required
                        value={data.name}
                        onChange={(e) => setData("name", e.target.value)}
                        sx={{ mb: 2 }}
                    />
                    <TextField
                        label="Опис квесту"
                        variant="outlined"
                        fullWidth
                        required
                        multiline
                        rows={4}
                        value={data.description}
                        onChange={(e) => setData("description", e.target.value)}
                        sx={{ mb: 2 }}
                    />

                    <Box display="flex" gap={2}>
                        <Button
                            variant="contained"
                            startIcon={<SaveIcon />}
                            onClick={handleSubmit}
                            sx={{
                                backgroundColor: "#66bb6a",
                                borderRadius: "30px",
                            }}
                        >
                            Зберегти
                        </Button>
                        <Button
                            variant="outlined"
                            startIcon={<CloseIcon />}
                            onClick={() => window.history.back()}
                            sx={{
                                borderColor: "#d32f2f",
                                color: "#d32f2f",
                                borderRadius: "30px",
                            }}
                        >
                            Скасувати
                        </Button>
                    </Box>
                </Box>
            </Container>
        </Box>
    );
}

export default CreateQuest;
