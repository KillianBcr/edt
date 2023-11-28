import React, { useState, useEffect } from 'react';
import "../../styles/repartition.css";
import { Link } from 'wouter';
import {
    fetchWishes,
    getMe,
    deleteWish,
    updateWish,
    fetchWishesForUser,
    getSubjectName,
    getGroupName,
    getSubjectYear,
    getCurrentYear
} from "../services/api";
import Button from '@mui/material/Button';
import TextField from '@mui/material/TextField';
import Dialog from '@mui/material/Dialog';
import DialogActions from '@mui/material/DialogActions';
import DialogContent from '@mui/material/DialogContent';
import DialogTitle from '@mui/material/DialogTitle';
import Pagination from '@mui/material/Pagination';
import Stack from '@mui/material/Stack';
function SubjectLoader({ subjectId, onSubjectLoad }) {
    useEffect(() => {
        const loadSubject = async () => {
            const subjectName = await getSubjectName(subjectId);
            onSubjectLoad(subjectName);
        };
        loadSubject();
    }, [subjectId, onSubjectLoad]);

    return null;
}

function GroupLoader({ groupType, onGroupLoad }) {
    useEffect(() => {
        const loadGroup = async () => {
            const groupName = await getGroupName(groupType);
            onGroupLoad(groupName);
        };

        loadGroup();
    }, [groupType, onGroupLoad]);

    return null;
}

function Repartition() {
    const [wishes, setWishes] = useState([]);
    const [userId, setUserId] = useState(null);
    const [open, setOpen] = React.useState(false);
    const [selectedWishId, setSelectedWishId] = useState(null);
    const [modifiedChosenGroups, setModifiedChosenGroups] = useState('');
    const [modifiedGroupName, setModifiedGroupName] = useState('');
    const [currentPage, setCurrentPage] = useState(1);
    const wishesPerPage = 3;

    const indexOfLastWish = currentPage * wishesPerPage;
    const indexOfFirstWish = indexOfLastWish - wishesPerPage;
    const currentWishes = wishes.slice(indexOfFirstWish, indexOfLastWish);

    const paginate = (pageNumber) => setCurrentPage(pageNumber);


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

    const handleSubjectLoad = (subjectId, subjectName) => {
        setWishes((prevWishes) => prevWishes.map((wish) => (wish.subjectId === subjectId ? { ...wish, subjectName } : wish)));
    };

    const handleGroupLoad = (groupType, groupName) => {
        setWishes((prevWishes) => prevWishes.map((wish) => (wish.groupeType === groupType ? { ...wish, groupName } : wish)));
    };

    useEffect(() => {
        const fetchData = async () => {
            try {
                const userData = await getMe();
                if (userData) {
                    const currentUserID = userData.id;
                    setUserId(currentUserID);

                    const wishData = await fetchWishesForUser(currentUserID);
                    setWishes(wishData);
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
                setWishes((prevWishes) => prevWishes.filter((wish) => wish.id !== wishId));
            } catch (error) {
                if (error.message === "JSON.parse: unexpected end of data at line 1 column 1 of the JSON data") {
                    setWishes((prevWishes) => prevWishes.filter((wish) => wish.id !== wishId));
                    console.warn("Suppression du vœu réussie, réponse vide du serveur.");
                } else {
                    console.error("Error deleting wish:", error);
                }
            }
        }
    };

    const handleSaveWish = async () => {
        handleClose();
        if (selectedWishId) {
            try {
                const updatedWish = {
                    chosenGroups: parseInt(modifiedChosenGroups, 10),
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

    function truncateSubjectName(subjectName) {
        const maxLength = 40;
        if (subjectName.length <= maxLength) {
            return subjectName;
        } else {
            return subjectName.substring(0, maxLength) + "...";
        }
    }


    return (
        <div className="table-container">
            <h2 className={"repartition"}>Répartition de vos heures</h2>
            <table>
                <thead>
                <tr>
                    <th>Cours</th>
                    <th>Groupes</th>
                    <th>Etat</th>
                    <th>Modification</th>
                </tr>
                </thead>
                <tbody>
                {wishes.length === 0 ? (
                            <tr>
                                <td colSpan="4">
                                    <Link to="/react/semesters/1" className="btn btn-primary">Ajouter des heures</Link>
                                </td>
                            </tr>
                ) : (
                    <>
                        {currentWishes.map(wish => (
                            <tr key={wish.id}>
                                <td>
                                    {wish.subjectName ? (
                                        wish.subjectName
                                    ) : (
                                        <React.Suspense fallback="Chargement...">
                                            <SubjectLoader
                                                subjectId={wish.subjectId}
                                                onSubjectLoad={(subjectName) =>
                                                    handleSubjectLoad(wish.subjectId, truncateSubjectName(subjectName))
                                                }
                                            />
                                        </React.Suspense>

                                    )}
                                </td>
                                <td>
                                    {wish.chosenGroups} groupe(s) de {wish.groupName ? (
                                    wish.groupName
                                ) : (
                                    <React.Suspense fallback="Chargement...">
                                        <GroupLoader groupType={wish.groupeType} onGroupLoad={(groupName) => handleGroupLoad(wish.groupeType, groupName)} />
                                    </React.Suspense>
                                )}
                                </td>
                                <td>
                                    {wish.isAccepted === true ? (
                                        <span className="badge bg-success font-weight-normal" style={{ fontSize: '1rem' }}>
                                        Accepté
                                    </span>
                                    ) : wish.isAccepted === false ? (
                                        <span className="badge bg-danger font-weight-normal" style={{ fontSize: '1rem' }}>
                                        Refusé
                                    </span>
                                    ) : (
                                        <span className="badge bg-warning font-weight-normal" style={{ fontSize: '1rem' }}>
                                        En attente
                                    </span>
                                    )}
                                </td>
                                <td>
                                    <button id="repartition_btn" className="btn btn-primary" onClick={() => handleOpen(wish.id)}>Modifier</button>
                                    <button id="repartition_btn" className="btn btn-danger" onClick={() => handleDeleteWish(wish.id)}>Supprimer</button>
                                </td>
                            </tr>
                        ))}
                        <tr>
                            <td colSpan="4">
                                <Link to="/react/semesters/1" className="btn btn-primary">Ajouter des heures</Link>
                            </td>
                        </tr>
                    </>
                )}
                </tbody>
            </table>
            <div className="pagination-container">
                <Stack spacing={2}>
                    <Pagination
                        count={Math.ceil(wishes.length / wishesPerPage)}
                        size="large"
                        page={currentPage}
                        onChange={(event, page) => paginate(page)}
                    />
                </Stack>
            </div>

            <Dialog open={open} onClose={handleClose}>
                <DialogTitle>Modifier le vœu</DialogTitle>
                <DialogContent>
                    <TextField
                        autoFocus
                        margin="dense"
                        id="modifiedChosenGroups"
                        label="Nombre de groupe"
                        type="number"
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
                        disabled={true}
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
