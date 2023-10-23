import React, { useState, useEffect } from 'react';
import { getMe, fetchGroups } from '../services/api';

function WishForm({ subjectId }) {
    const [chosenGroups, setChosenGroups] = useState('');
    const [groupeType, setGroupeType] = useState('');
    const [groupeTypes, setGroupeTypes] = useState([]);
    const [groups, setGroups] = useState([]);
    const [user, setUser] = useState(null);

    useEffect(() => {
        getMe().then((userData) => {
            setUser(userData);
        });
        fetchGroups().then((data) => {
            setGroupeTypes(data);
        });
        fetchGroups().then((data) => {
            setGroups(data);
        });
    }, []);

    const handleSubmit = (event) => {
        event.preventDefault();

        const formData = {
            chosenGroups,
            subjectId,
            groupeType,
        };

        fetch('/api/wishes', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${user.token}`
            },
            body: JSON.stringify(formData),
        })
            .then(response => {
                if (response.ok) {
                    return response.json();
                } else {
                    console.log("ChosenGroups : ", chosenGroups);
                    console.log("GroupeType : ", groupeType);
                    console.log("Subject : ", subjectId);
                    throw new Error('Erreur de requête');
                }
            })
            .then(data => {
            })
            .catch(error => {
                console.error('Une erreur s\'est produite :', error);
            });
    };

    return (
        <form onSubmit={handleSubmit}>
            <div className="form-group">
                <label htmlFor="chosenGroups">Nombre de groupe</label>
                <input
                    type="number"
                    id="chosenGroups"
                    name="chosenGroups"
                    value={chosenGroups}
                    onChange={(e) => setChosenGroups(e.target.value)}
                    className="form-control"
                />
            </div>

            <div className="form-group">
                <label htmlFor="groupeType">Groupe Type</label>
                <select
                    id="groupeType"
                    name="groupeType"
                    value={groupeType}
                    onChange={(e) => setGroupeType(e.target.value)}
                    className="form-control"
                >
                    <option value="">Sélectionnez un groupe</option>
                    {groups.map((group) => (
                        <option key={group.id} value={group.type}>
                            {group.type}
                        </option>
                    ))}
                </select>
            </div>

            <button type="submit" className="btn btn-primary">Postuler</button>
        </form>
    );
}

export default WishForm;
