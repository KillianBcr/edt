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

    useEffect(() => {
        const fetchData = async () => {
            try {
                const userData = await getMe();
                setUser(userData);

                if (userData && userData.id) {
                    const wishes = await getLoggedInUserWishes(userData.id);
                    const userWishesFiltered = wishes["hydra:member"].filter(wish => wish.wishUser === `/api/users/${userData.id}`);
                    setUserWishes(userWishesFiltered);

                    const allGroups = await fetchGroups(); // Fetch all groups
                    const allWeeks = await fetchWeeks(); // Fetch all weeks

                    const relevantGroupData = allGroups["hydra:member"].filter(group => {
                        const groupId = extractGroupId(group["@id"]);
                        return userWishesFiltered.some(wish => extractGroupId(wish.groupeType) === groupId);
                    });

                    const wishesDetails = await Promise.all(userWishesFiltered.map(async (wish) => {
                        const groupId = extractGroupId(wish.groupeType);

                        if (groupId) {
                            // Find the relevant group data locally
                            const groupData = relevantGroupData.find(group => group["@id"] === `/api/groups/${groupId}`);
                            if (groupData) {
                                // Find weeks details
                                const weeksDetails = allWeeks['hydra:member']
                                    .filter(week => groupData.weeks.includes(week['@id']));

                                // Sum the number of hours for each week
                                const totalHours = weeksDetails.reduce((total, week) => total + week.numberHours, 0);

                                // Multiply the total hours by chosenGroups
                                const multipliedHours = totalHours * wish.chosenGroups;

                                console.log(wish.chosenGroups, totalHours, multipliedHours);

                                setHourlyRates(prevRates => [...prevRates, multipliedHours]);
                                setAssociatedWeeks(prevWeeks => [...prevWeeks, weeksDetails]);

                                return { wish, groupData, weeksDetails };
                            }
                        }
                    }));
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

            const dataValues = labels.map((weekNumber) => {
                const totalHoursForWeek = flattenWeeks
                    .filter((week) => week.weekNumber === weekNumber)
                    .reduce((total, week) => total + week.numberHours, 0);

                console.log(`Week ${weekNumber}: Total Hours - ${totalHoursForWeek}`);

                return totalHoursForWeek;
            });


            const data = {
                labels: labels,
                datasets: [{
                    label: 'My First Dataset',
                    data: dataValues,
                    fill: false,
                    borderColor: 'rgb(0, 200, 255)',
                    backgroundColor: 'rgb(0, 200, 255)',
                    tension: 0.1
                }]
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
                                stepSize: 1
                            }
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
    }, [showChart, associatedWeeks]);

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
