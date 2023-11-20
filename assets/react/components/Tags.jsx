import React, { useState } from 'react';
import TextField from '@mui/material/TextField';
import Button from '@mui/material/Button';
import Chip from '@mui/material/Chip';
import Box from '@mui/material/Box';
import {addTagToDatabase, deleteTagFromDatabase} from "../services/api";

function TagForm() {
    const [tagInput, setTagInput] = useState('');
    const [tags, setTags] = useState([]);

    const handleTagInputChange = (event) => {
        setTagInput(event.target.value);
    };

    const handleAddTag = () => {
        if (tagInput.trim() !== '') {
            addTagToDatabase(tagInput)
                .then((newTag) => {
                    // Faites quelque chose avec la réponse de la base de données si nécessaire
                    console.log('Tag added successfully:', newTag);
                })
                .catch((error) => {
                    console.error('Error adding tag:', error);
                });
            setTags([...tags, tagInput]);
            setTagInput('');
        }
    };

    const handleDeleteTag = (tagToDelete) => () => {
        // Obtenez l'ID du tag à partir de la base de données
        const tagId = tagToDelete.id; // Assurez-vous que votre objet tag a une propriété id

        // Supprimez le tag de la base de données
        deleteTagFromDatabase(tagId)
            .then(() => {
                // Mettez à jour l'état des tags en supprimant le tag
                console.log(tagId)
                setTags((prevTags) => prevTags.filter((tag) => tag.id !== tagId));
            })
            .catch((error) => {
                console.error('Error deleting tag:', error);
            });
    };

    return (
        <Box>
            <div>
                {tags.map((tag, index) => (
                    <Chip
                        key={index}
                        label={tag}
                        onDelete={handleDeleteTag({tag})}
                        style={{ margin: '4px' }}
                    />
                ))}
            </div>
            <div>
                <TextField
                    label="Ajouter un filtre"
                    variant="outlined"
                    value={tagInput}
                    onChange={handleTagInputChange}
                />
                <Button
                    variant="contained"
                    color="primary"
                    onClick={handleAddTag}
                    style={{ marginLeft: '8px' }}
                >
                    Ajouter
                </Button>
            </div>
        </Box>
    );
}

export default TagForm;
