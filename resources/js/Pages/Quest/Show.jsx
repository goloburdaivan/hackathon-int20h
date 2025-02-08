import React, { useState } from "react";
import { useForm } from "@inertiajs/react";
import {
    Box,
    Typography,
    Container,
    CssBaseline,
    Paper,
    Grid,
    Button,
    TextField,
    MenuItem
} from "@mui/material";
import PlayArrowIcon from "@mui/icons-material/PlayArrow";

function QuestView({ quest }) {
    const { data, setData, post } = useForm({
        duration: "30",
        max_participants: "1",
    });

    const handleStartQuest = () => {
        post(`/quests/${quest.id}/start`);
    };

    return (
        <Box sx={{ minHeight: "100vh", backgroundColor: "#e8f5e9", py: 5 }}>
            <CssBaseline />
            <Container component="main" maxWidth="md">
                <Paper sx={{ padding: "30px", borderRadius: "16px", boxShadow: 5, backgroundColor: "#ffffff" }}>
                    <Typography variant="h4" sx={{ fontWeight: "bold", color: "#388e3c", mb: 2 }}>
                        {quest.name}
                    </Typography>
                    <Typography variant="body1" sx={{ color: "#666", mb: 3 }}>
                        {quest.description}
                    </Typography>
                    <Typography variant="body2" sx={{ color: "#888", mb: 3 }}>
                        Количество вопросов: {quest.questions_count}
                    </Typography>
                    <Grid container spacing={2} sx={{ mb: 3 }}>
                        <Grid item xs={6}>
                            <TextField
                                select
                                fullWidth
                                label="Продолжительность (минут)"
                                value={data.duration}
                                onChange={(e) => setData("duration", e.target.value)}
                            >
                                {["30", "60", "90", "120"].map((option) => (
                                    <MenuItem key={option} value={option}>{option}</MenuItem>
                                ))}
                            </TextField>
                        </Grid>
                        <Grid item xs={6}>
                            <TextField
                                select
                                fullWidth
                                label="Количество участников"
                                value={data.max_participants}
                                onChange={(e) => setData("max_participants", e.target.value)}
                            >
                                {["1", "2", "3", "4", "5"].map((option) => (
                                    <MenuItem key={option} value={option}>{option}</MenuItem>
                                ))}
                            </TextField>
                        </Grid>
                    </Grid>
                    <Button
                        variant="contained"
                        startIcon={<PlayArrowIcon />}
                        sx={{ backgroundColor: "#66bb6a", borderRadius: "30px" }}
                        onClick={handleStartQuest}
                    >
                        Начать квест
                    </Button>
                </Paper>
            </Container>
        </Box>
    );
}

export default QuestView;
