import React, { useState, useEffect } from 'react';
import { getMe, getLoggedInUserWishes, getGroup } from '../services/api';
import "../../styles/me.css";

function Me() {
    const [user, setUser] = useState(null);
    const [userWishes, setUserWishes] = useState([]);
    const [hourlyRates, setHourlyRates] = useState([]);
    const [associatedWeeks, setAssociatedWeeks] = useState([]);

    useEffect(() => {
        const fetchData = async () => {
            try {
                const userData = await getMe();
                setUser(userData);

                if (userData && userData.id) {
                    const wishes = await getLoggedInUserWishes(userData.id);
                    const userWishesFiltered = wishes["hydra:member"].filter(wish => wish.wishUser === `/api/users/${userData.id}`);
                    setUserWishes(userWishesFiltered);

                    // Use Promise.all to fetch details for all wishes concurrently
                    const wishesDetails = await Promise.all(userWishesFiltered.map(async (wish) => {
                        const groupId = extractGroupId(wish.groupeType);

                        if (groupId) {
                            const groupData = await getGroup(groupId);
                            setHourlyRates(prevRates => [...prevRates, groupData.hourlyRate]);

                            // Use Promise.all to fetch details for all weeks concurrently
                            const weeksDetails = await Promise.all(groupData.weeks.map(getWeekDetails));
                            setAssociatedWeeks(prevWeeks => [...prevWeeks, weeksDetails]);

                            return { wish, groupData, weeksDetails };
                        }
                    }));

                    console.log("User Wishes Details:", wishesDetails);
                }
            } catch (error) {
                console.error("Erreur lors de la récupération des données :", error);
            }
        };

        fetchData();
    }, []);

    function extractGroupId(groupType) {
        const matches = groupType.match(/\/api\/groups\/(\d+)/);
        return matches && matches[1] ? matches[1] : null;
    }

    function getWeekDetails(weekUrl) {
        const weekId = extractWeekId(weekUrl);

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
                    </div>
                    {userWishes.map((wishDetails, index) => (
                        <div key={index}>
                            <p>Wish Details: {JSON.stringify(wishDetails.wish)}</p>
                            {hourlyRates[index] && <p>Hourly Rate: {hourlyRates[index]}</p>}
                            {associatedWeeks[index] && (
                                <div>
                                    <p>Associated Weeks:</p>
                                    <ul>
                                        {associatedWeeks[index].map((weekDetails, weekIndex) => (
                                            <li key={weekIndex}>
                                                {weekDetails && (
                                                    <p>Week Number: {weekDetails.weekNumber}, Number of Hours: {weekDetails.numberHours}</p>
                                                )}
                                            </li>
                                        ))}
                                    </ul>
                                </div>
                            )}
                        </div>
                    ))}
                </div>
            )}
        </div>
    );
}

export default Me;
