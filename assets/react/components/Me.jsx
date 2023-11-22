import React, { useState, useEffect } from 'react';
import { getMe, getLoggedInUserWishes, getGroup } from '../services/api';
import "../../styles/me.css";

function Me() {
    const [user, setUser] = useState(null);
    const [wishData, setWishData] = useState(null);
    const [hourlyRate, setHourlyRate] = useState(null);

    useEffect(() => {
        getMe().then((userData) => {
            setUser(userData);

            if (userData && userData.id) {
                getLoggedInUserWishes().then((wishes) => {
                    const userWish = wishes["hydra:member"].find(wish => wish.wishUser === `/api/users/${userData.id}`);

                    if (userWish) {
                        const groupId = extractGroupId(userWish.groupeType);

                        if (groupId) {
                            getGroup(groupId).then((groupData) => {
                                console.log("userWish :", userWish);
                                console.log("groupData :", groupData);
                                if (groupData) {
                                    setHourlyRate(groupData.hourlyRate);
                                    console.log("Taux horaire :", hourlyRate);
                                }
                            }).catch((groupError) => {
                                console.error("Erreur lors de la récupération des données du groupe :", groupError);
                            });
                        }
                    } else {
                        console.log("Aucun vœu trouvé pour l'utilisateur connecté.");
                    }
                }).catch((wishError) => {
                    console.error("Erreur lors de la récupération des données du vœu :", wishError);
                });
            }
        });
    }, []);

    function extractGroupId(groupType) {
        const matches = groupType.match(/\/api\/groups\/(\d+)/);
        if (matches && matches[1]) {
            return matches[1];
        }
        return null;
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
                </div>
            )}
        </div>
    );
}

export default Me;
