import React, { useState, useEffect } from 'react';
import {getMe, getLoggedInUserWishes, getGroup} from '../services/api';
import "../../styles/me.css";

function Me() {
    const [user, setUser] = useState(null);
    const [wishData, setWishData] = useState(null);
    const [hourlyRate, setHourlyRate] = useState(null);

    useEffect(() => {
        getMe().then((userData) => {
            setUser(userData);

            if (userData && userData.id) {
                // Récupérer le vœu unique pour l'utilisateur
                getLoggedInUserWishes().then((wish) => {
                    console.log("wish :", wish);
                    // Assumer que 'groupeType' contient le groupId du vœu
                    const groupId = extractGroupId(wish["hydra:member"][0].groupeType);
                    console.log("groupId :", groupId);
                    if (groupId) {
                        // Récupérer les détails du groupe en utilisant le groupId du vœu
                        getGroup(groupId).then((groupData) => {
                            console.log("userWish :", wish);
                            console.log("groupData :", groupData);

                            if (groupData) {
                                // Définir hourlyRate à partir des détails du groupe
                                setHourlyRate(groupData.hourlyRate);
                                console.log("Taux horaire :", hourlyRate);
                            }
                        }).catch((groupError) => {
                            console.error("Erreur lors de la récupération des données du groupe :", groupError);
                        });
                    }
                }).catch((wishError) => {
                    console.error("Erreur lors de la récupération des données du vœu :", wishError);
                });
            }
        });
    }, []);

    function extractGroupId(groupType) {
        // Utiliser une expression régulière pour extraire le nombre de la chaîne "/api/groups/X"
        const matches = groupType.match(/\/api\/groups\/(\d+)/);
        if (matches && matches[1]) {
            return matches[1];
        }
        return null; // ou lancez une erreur si la chaîne n'est pas dans le format attendu
    }


    return (
        <div>
            {user && (
                <div className="meContainer">
                    <div>
                        <p>Total : {user.minHours}</p>
                        <p>Min / Max : {user.minHours} / {user.maxHours}</p>
                        <p>Hourly Rate: {hourlyRate}</p>
                    </div>
                    {wishData && (
                        <div>
                            <p>Groupe Type: {wishData.groupeType}</p>
                        </div>
                    )}
                    {hourlyRate && (
                        <div>
                            <p>Hourly Rate: {hourlyRate}</p>
                        </div>
                    )}
                </div>
            )}
        </div>
    );
}

export default Me;