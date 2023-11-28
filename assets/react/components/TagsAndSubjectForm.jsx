import React, { useEffect, useState } from 'react';
import { fetchTags, fetchSemesters, addTagToSubject, fetchSubjectsForSemester } from '../services/api';
import { useRoute } from 'wouter';

function TagAndSubjectForm() {
    const [tags, setTags] = useState([]);
    const [semesters, setSemesters] = useState([]);
    const [subjects, setSubjects] = useState([]);
    const [selectedTag, setSelectedTag] = useState('');
    const [selectedSubject, setSelectedSubject] = useState('');
    const [, params] = useRoute('/react/semesters/:semesterId'); // Remplacez 'votre-route' par le chemin réel

    useEffect(() => {
        // Récupérer les tags et les semestres au chargement du composant
        fetchTags().then(data => setTags(data['hydra:member']));
        fetchSemesters().then(data => setSemesters(data['hydra:member']));
        // Récupérer les matières pour le semestre sélectionné
        if (params && params.semesterId) {
            fetchSubjectsForSemester(params.semesterId)
                .then(data => {
                    setSubjects(data['subjects']);
                })
                .catch(error => console.error('Error fetching subjects:', error));
        }
    }, []);

    const handleTagChange = (event) => {
        setSelectedTag(event.target.value);
    };

    const handleSubjectChange = (event) => {
        setSelectedSubject(event.target.value);
    };

    const handleAddTagToSubject = () => {
        // Appeler la méthode API pour ajouter le tag sélectionné à la matière sélectionnée
        console.log("1")
        if (selectedTag && selectedSubject) {
            console.log("2")
            addTagToSubject(selectedTag, selectedSubject)
                .then(() => {
                    console.log("3")
                    // Gérer le succès, peut-être mettre à jour l'interface utilisateur ou afficher un message
                    console.log('Tag ajouté à la matière avec succès.');
                })
                .catch((error) => {
                    console.error('Erreur lors de l\'ajout du tag à la matière :', error);
                });
        }
    };


    return (
        <div className="formsAndButton" style={{display: "flex", justifyContent: "flex-end", alignItems: "baseline", width: "100%"}}>
            <div style={{marginLeft: "10px", marginRight: "10px"}}>
                <label htmlFor="tagDropdown">Tag :</label>
                <select id="tagDropdown" onChange={handleTagChange} value={selectedTag}>
                    <option value="">Sélectionnez un Tag</option>
                    {tags.map((tag) => (
                        <option key={tag.id} value={tag.id}>
                            {tag.name}
                        </option>
                    ))}
                </select>
            </div>
            <div style={{marginLeft: "10px", marginRight: "10px"}}>
                <label htmlFor="subjectDropdown">Matière :</label>
                <select id="subjectDropdown" onChange={handleSubjectChange} value={selectedSubject}>
                    <option value="">Sélectionnez une Matière</option>
                    {subjects.map((subject) => (
                        <option key={subject.id} value={subject.id}>
                            {subject.name}
                        </option>
                    ))}
                </select>
            </div>
            <div>
                <button onClick={handleAddTagToSubject}>Ajouter un Tag à la Matière</button>
            </div>
        </div>
    );
}

export default TagAndSubjectForm;
