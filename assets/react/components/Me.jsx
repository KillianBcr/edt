import React, { useState, useEffect } from 'react';
import {getMe, getLoggedInUserWishes, fetchWeeks, fetchGroups} from '../services/api';
import { Chart } from 'chart.js/auto';
import "../../styles/me.css";

function Me() {
    const [user, setUser] = useState(null);
    const [userWishes, setUserWishes] = useState([]);
    const [hourlyRates, setHourlyRates] = useState([]);
    const [associatedWeeks, setAssociatedWeeks] = useState([]);
    const [showChart, setShowChart] = useState(false);
    const [wishesDetails, setWishesDetails] = useState([]); // Declare wishesDetails state

    useEffect(() => {
        const fetchData = async () => {
            try {
                const userData = await getMe();
                setUser(userData);

                if (userData && userData.id) {
                    const wishes = await getLoggedInUserWishes(userData.id);
                    const userWishesFiltered = wishes["hydra:member"].filter(wish => wish.wishUser === `/api/users/${userData.id}`);
                    setUserWishes(userWishesFiltered);

                    const allGroups = await fetchGroups();
                    const allWeeks = await fetchWeeks();

                    const relevantGroupData = allGroups["hydra:member"].filter(group => {
                        const groupId = extractGroupId(group["@id"]);
                        return userWishesFiltered.some(wish => extractGroupId(wish.groupeType) === groupId);
                    });

                    const details = await Promise.all(userWishesFiltered.map(async (wish) => {
                        const groupId = extractGroupId(wish.groupeType);

                        if (groupId) {
                            const groupData = relevantGroupData.find(group => group["@id"] === `/api/groups/${groupId}`);
                            if (groupData) {
                                const weeksDetails = allWeeks['hydra:member']
                                    .filter(week => groupData.weeks.includes(week['@id']));

                                const totalHours = weeksDetails.reduce((total, week) => total + week.numberHours, 0);

                                const multipliedHours = totalHours * wish.chosenGroups;

                                setHourlyRates(prevRates => [...prevRates, multipliedHours]);
                                setAssociatedWeeks(prevWeeks => [...prevWeeks, weeksDetails]);

                                return { wish, groupData, weeksDetails };
                            }
                        }
                    }));

                    // Set wishesDetails state after fetching data
                    setWishesDetails(details);
                }
            } catch (error) {
                console.error("Erreur lors de la récupération des données :", error);
            }
        };

        fetchData();
    }, []);


    useEffect(() => {
        let myChart;

        if (showChart && associatedWeeks.length > 0) {
            const flattenWeeks = associatedWeeks.flat();

            const labels = Array.from({ length: 52 }, (_, index) => index + 1);

            // Create a data structure to store total hours for each subject (matière)
            const subjectsData = {};

            wishesDetails.forEach(({ wish, groupData, weeksDetails }) => {
                const chosenGroups = wish.chosenGroups;

                weeksDetails.forEach((week) => {
                    const subjectId = week.subjectId;

                    if (!subjectsData[subjectId]) {
                        subjectsData[subjectId] = {
                            label: `Subject ${subjectId}`,
                            data: Array(52).fill(0), // Initialize data array for each subject
                        };
                    }

                    subjectsData[subjectId].data[week.weekNumber - 1] += week.numberHours * chosenGroups;
                });
            });

            // Convert subjectsData into Chart.js format
            const data = {
                labels: labels,
                datasets: Object.values(subjectsData),
            };

            const config = {
                type: 'bar',
                data: data,
                options: {
                    scales: {
                        x: {
                            type: 'linear',
                            position: 'bottom',
                            ticks: {
                                stepSize: 1,
                            },
                        },
                    },
                },
            };

            if (myChart) {
                myChart.destroy();
            }

            myChart = new Chart('myChart', config);
        }

        return () => {
            if (myChart) {
                myChart.destroy();
            }
        };
    }, [showChart, associatedWeeks, wishesDetails]);

    function extractGroupId(groupType) {
        const matches = groupType.match(/\/api\/groups\/(\d+)/);
        return matches && matches[1] ? matches[1] : null;
    }

    const handleShowChart = () => {
        setShowChart(prevShowChart => !prevShowChart);
    };

    return (
        <div>
            {user && (
                <div className="meContainer">
                    <div>
                        <p>Total : {user.minHours}</p>
                        <p>Min / Max : {user.minHours} / {user.maxHours}</p>
                        <button onClick={handleShowChart}>
                            {showChart ? 'Cacher le graphique' : 'Afficher le graphique'}
                        </button>
                        {showChart && <canvas id="myChart" width="400" height="400"></canvas>}
                    </div>
                </div>
            )}
        </div>
    );
}

export default Me;
