import React, {useEffect, useState} from 'react';
import TextField from '@mui/material/TextField';
import Button from '@mui/material/Button';
import Chip from '@mui/material/Chip';
import Box from '@mui/material/Box';
import {addTagToDatabase, deleteTagFromDatabase, fetchSemesters, fetchTags} from "../services/api";
import {Link} from "wouter";

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
                    console.log('Tag added successfully:', newTag);
                    setTags([...tags, {id: newTag.id, name: newTag.name}])
                })
                .catch((error) => {
                    console.error('Error adding tag:', error);
                });
            setTagInput('');
        }
    };

    const handleDeleteTag = (tagToDelete) => () => {
        const tagId = tagToDelete.id;
        console.log(tagToDelete)
        deleteTagFromDatabase(tagId)
            .then(() => {
                setTags((prevTags) => prevTags.filter((tag) => tag.id !== tagId));
            })
            .catch((error) => {
                console.error('Error deleting tag:', error);
            });
    };

    const [tagsList] = useState(null);

    useEffect(() => {
        fetchTags().then((data) => {
            setTags((prevTags) => [...prevTags, ...data["hydra:member"]]);
        });
    }, []);

    return (
        <Box>
            <div>
                {tags.length === 0 ? 'Loading...' :
                    tags.map((tag, index) => (
                            <Chip
                                key={index}
                                label={tag.name}
                                onDelete={handleDeleteTag(tag)}
                                style={{ margin: '4px' }}
                            />
                        ))
                }
            </div>
            <div>
                <TextField
                    label="Ajouter un tag"
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
