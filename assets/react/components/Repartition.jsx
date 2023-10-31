import React, { useState, useEffect } from 'react';
import "../../styles/repartition.css";
import { Link } from 'wouter';
import { fetchWishes, getMe, getSubject, getSubjectGroup, deleteWish, updateWish } from "../services/api";
import Button from '@mui/material/Button';
import TextField from '@mui/material/TextField';
import Dialog from '@mui/material/Dialog';
import DialogActions from '@mui/material/DialogActions';
import DialogContent from '@mui/material/DialogContent';
import DialogTitle from '@mui/material/DialogTitle';

function Repartition() {
    const [wishes, setWishes] = useState([]);
    const [userId, setUserId] = useState(null);
    const [open, setOpen] = React.useState(false);
    const [modifiedValue, setModifiedValue] = useState('');
    const [selectedWishId, setSelectedWishId] = useState(null);
    const [modifiedChosenGroups, setModifiedChosenGroups] = useState('');
    const [modifiedGroupName, setModifiedGroupName] = useState('');


    const handleOpen = (wishId) => {
        setSelectedWishId(wishId);
        setOpen(true);
        const selectedWish = wishes.find(wish => wish.id === wishId);
        if (selectedWish) {
            setModifiedChosenGroups(selectedWish.chosenGroups);
            setModifiedGroupName(selectedWish.groupName);
        }
    };

    const handleClose = () => {
        setSelectedWishId(null);
        setOpen(false);
        setModifiedChosenGroups('');
        setModifiedGroupName('');
    };


    useEffect(() => {
        const fetchData = async () => {
            try {
                const userData = await getMe();
                if (userData) {
                    const currentUserID = userData.id;
                    setUserId(currentUserID);
                    const wishData = await fetchWishes();
                    if (wishData && Array.isArray(wishData['hydra:member'])) {
                        const userWishes = wishData['hydra:member'].filter(wish => {
                            return wish.wishUser === `/api/users/${currentUserID}`;
                        });

                        const wishesWithSubjects = await Promise.all(userWishes.map(async wish => {
                            const subjectResponse = await getSubject(wish.subjectId);
                            const subjectGroupResponse = await getSubjectGroup(wish.groupeType);

                            if (subjectResponse) {
                                wish.subjectName = subjectResponse.name;
                                wish.subjectCode = subjectResponse.subjectCode
                                wish.groupName = subjectGroupResponse.type;
                            }
                            return wish;
                        }));

                        setWishes(wishesWithSubjects);
                    }
                }
            } catch (error) {
                console.error("Error fetching data:", error);
            }
        };
        fetchData();
    }, []);

    const handleDeleteWish = async (wishId) => {
        const confirmed = window.confirm("Voulez-vous vraiment supprimer ce vœu ?");
        if (confirmed) {
            try {
                await deleteWish(wishId);
                setWishes(wishes.filter(wish => wish.id !== wishId));
            } catch (error) {
                console.error("Error deleting wish:", error);
            }
        }
    };

    const handleSaveWish = async () => {
        handleClose();
        if (selectedWishId) {
            try {
                const updatedWish = {
                    chosenGroups: modifiedChosenGroups,
                    groupName: modifiedGroupName
                };
                await updateWish(selectedWishId, updatedWish);
                setWishes(wishes.map(wish =>
                    wish.id === selectedWishId
                        ? { ...wish, chosenGroups: modifiedChosenGroups, groupName: modifiedGroupName }
                        : wish
                ));
            } catch (error) {
                console.error("error updating wish:", error);
            }
        }
    };

    return (
        <div className="table-container">
            <h2 className={"repartition"}>Répartition de vos heures</h2>
            <table>
                <thead>
                <tr>
                    <th>Cours</th>
                    <th>Groupes</th>
                    <th>Modification</th>
                </tr>
                </thead>
                <tbody>
                {wishes.map(wish => (
                    <tr key={wish.id}>
                        <td>{wish.subjectName}</td>
                        <td>{wish.chosenGroups} groupes de {wish.groupName} </td>
                        <td>
                            <button className="modifier-button" onClick={() => handleOpen(wish.id)}>Modifier</button>
                            <button className="supprimer-button" onClick={() => handleDeleteWish(wish.id)}>Supprimer</button>
                        </td>
                    </tr>
                ))}
                <tr>
                    <td>
                        <Link to="/react/semesters" className="ajouter-button">Ajouter des heures</Link>
                    </td>
                </tr>
                </tbody>
            </table>
            <Dialog open={open} onClose={handleClose}>
                <DialogTitle>Modifier le vœu</DialogTitle>
                <DialogContent>
                    <TextField
                        autoFocus
                        margin="dense"
                        id="modifiedChosenGroups"
                        label="Nombre de groupe"
                        type="text"
                        fullWidth
                        variant="standard"
                        value={modifiedChosenGroups}
                        onChange={(e) => setModifiedChosenGroups(e.target.value)}
                    />
                    <TextField
                        margin="dense"
                        id="modifiedGroupName"
                        label="Type de groupe"
                        type="text"
                        fullWidth
                        variant="standard"
                        value={modifiedGroupName}
                        onChange={(e) => setModifiedGroupName(e.target.value)}
                    />
                </DialogContent>
                <DialogActions>
                    <Button onClick={handleClose}>Annuler</Button>
                    <Button onClick={handleSaveWish}>Enregistrer</Button>
                </DialogActions>
            </Dialog>
        </div>
    );
}

export default Repartition;
