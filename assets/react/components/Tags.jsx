import React, {useEffect, useState} from 'react';
import TextField from '@mui/material/TextField';
import Button from '@mui/material/Button';
import Chip from '@mui/material/Chip';
import Box from '@mui/material/Box';
import {addTagToDatabase, deleteTagFromDatabase, fetchSemesters, fetchTags} from "../services/api";
import {Link} from "wouter";

function TagForm({ changeSelectedTag }) {
    const [tagInput, setTagInput] = useState('');
    const [tags, setTags] = useState([]);
    const [selectedTags, setSelectedTags] = useState([]);
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

    useEffect(() => {
        fetchTags().then((data) => {
            setTags((prevTags) => [...prevTags, ...data["hydra:member"]]);
        });
    }, []);

    const handleTagClick = (tag) => {
        setSelectedTags((prevSelectedTags) => {
            if (prevSelectedTags.some((selectedTag) => selectedTag.id === tag.id)) {
                // Si le tag est déjà sélectionné, retirez-le
                console.log("delete filter")
                return prevSelectedTags.filter((selectedTag) => selectedTag.id !== tag.id);
            } else {
                // Sinon, ajoutez-le
                console.log("add filter")
                return [...prevSelectedTags, tag];
            }
        });

        changeSelectedTag([...selectedTags, tag]);
    }

    return (
        <Box>
            <div>
                {tags.length === 0 ? 'Loading...' :
                    tags.map((tag, index) => (
                            <Chip
                                key={index}
                                label={tag.name}
                                onClick={() => handleTagClick(tag)}
                                onDelete={handleDeleteTag(tag)}
                                style={{ margin: '4px', backgroundColor: selectedTags.some((selectedTag) => selectedTag.id === tag.id) ? '#5cb85c' : '#4a3d8a', color: 'white' }}
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
