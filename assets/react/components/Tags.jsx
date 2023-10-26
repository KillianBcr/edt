import React, { useState } from 'react';
import TextField from '@mui/material/TextField';
import Button from '@mui/material/Button';
import Chip from '@mui/material/Chip';
import Box from '@mui/material/Box';

function TagForm() {
    const [tagInput, setTagInput] = useState('');
    const [tags, setTags] = useState([]);

    const handleTagInputChange = (event) => {
        setTagInput(event.target.value);
    };

    const handleAddTag = () => {
        if (tagInput.trim() !== '') {
            setTags([...tags, tagInput]);
            setTagInput('');
        }
    };

    const handleDeleteTag = (tagToDelete) => () => {
        setTags((prevTags) => prevTags.filter((tag) => tag !== tagToDelete));
    };

    return (
        <Box>
            <div>
                {tags.map((tag, index) => (
                    <Chip
                        key={index}
                        label={tag}
                        onDelete={handleDeleteTag(tag)}
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
