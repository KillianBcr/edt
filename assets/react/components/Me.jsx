import React, { useState, useEffect } from 'react';
import { getMe, getLoggedInUserWishes, getGroup } from '../services/api';
import "../../styles/me.css";

function Me() {
    const [user, setUser] = useState(null);
    const [userWish, setUserWish] = useState(null);
    const [groupData, setGroupData] = useState(null);
    const [hourlyRate, setHourlyRate] = useState(null);
    const [associatedWeeks, setAssociatedWeeks] = useState(null);

    useEffect(() => {
        getMe().then((userData) => {
            setUser(userData);

            if (userData && userData.id) {
                getLoggedInUserWishes().then((wishes) => {
                    const wishForUser = wishes["hydra:member"].find(wish => wish.wishUser === `/api/users/${userData.id}`);

                    if (wishForUser) {
                        setUserWish(wishForUser);
                        const groupId = extractGroupId(wishForUser.groupeType);

                        if (groupId) {
                            getGroup(groupId).then((groupData) => {
                                setGroupData(groupData);

                                if (groupData) {
                                    setHourlyRate(groupData.hourlyRate);

                                    // Récupérer les détails des semaines associées au groupe
                                    Promise.all(groupData.weeks.map(weekUrl => getWeekDetails(weekUrl)))
                                        .then(weeksDetails => {
                                            console.log("Associated Weeks Details:", weeksDetails);
                                            setAssociatedWeeks(weeksDetails);
                                        })
                                        .catch(weeksError => {
                                            console.error("Erreur lors de la récupération des détails des semaines associées :", weeksError);
                                        });
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

    function getWeekDetails(weekUrl) {
        // Récupérer l'ID de la semaine à partir de l'URL
        const weekId = extractWeekId(weekUrl);

        // Récupérer les détails de la semaine à partir de l'ID
        if (weekId) {
            return fetch(`/api/weeks/${weekId}`, { credentials: "include" })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    } else {
                        return Promise.reject('Failed to fetch week details');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    }

    function extractWeekId(weekUrl) {
        const matches = weekUrl.match(/\/api\/weeks\/(\d+)/);
        return matches && matches[1] ? matches[1] : null;
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
                    {associatedWeeks && (
                        <div>
                            <p>Associated Weeks:</p>
                            <ul>
                                {associatedWeeks.map((weekDetails, index) => (
                                    <li key={index}>
                                        {weekDetails && (
                                            <p>Week Number: {weekDetails.weekNumber}, Number of Hours: {weekDetails.numberHours}</p>
                                        )}
                                    </li>
                                ))}
                            </ul>
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
