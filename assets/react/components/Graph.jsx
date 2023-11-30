import React, { useState, useEffect } from 'react';
import { getMe, getLoggedInUserWishes, fetchWeeks, fetchGroups } from '../services/api';
import { Chart } from 'chart.js/auto';
import "../../styles/me.css";

function Graph() {

    const [associatedWeeks, setAssociatedWeeks] = useState([]);
    const [wishesDetails, setWishesDetails] = useState([]);
    const [userWishesFiltered, setUserWishesFiltered] = useState([]);

    useEffect(() => {
        const fetchData = async () => {
            try {
                const userData = await getMe();

                if (userData && userData.id) {
                    const wishes = await getLoggedInUserWishes(userData.id);
                    const filteredWishes = wishes["hydra:member"].filter(wish => wish.wishUser === `/api/users/${userData.id}`);
                    setUserWishesFiltered(filteredWishes);

                    const allGroups = await fetchGroups();
                    const allWeeks = await fetchWeeks();

                    const relevantGroupData = allGroups["hydra:member"].filter(group => {
                        const groupId = extractGroupId(group["@id"]);
                        return filteredWishes.some(wish => extractGroupId(wish.groupeType) === groupId);
                    });

                    const details = await Promise.all(filteredWishes.map(async (wish) => {
                        const groupId = extractGroupId(wish.groupeType);

                        if (groupId) {
                            const groupData = relevantGroupData.find(group => group["@id"] === `/api/groups/${groupId}`);
                            if (groupData) {
                                const weeksDetails = allWeeks['hydra:member']
                                    .filter(week => groupData.weeks.includes(week['@id']));
                                setAssociatedWeeks(prevWeeks => [...prevWeeks, weeksDetails]);

                                return { wish, groupData, weeksDetails };
                            }
                        }
                    }));

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

        if (associatedWeeks.length > 0) {
            const flattenWeeks = associatedWeeks.flat();

            const labels = Array.from({ length: 52 }, (_, index) => index + 1);

            const subjectsData = {};

            wishesDetails.forEach(({ wish, groupData, weeksDetails }) => {
                const chosenGroups = wish.chosenGroups;

                weeksDetails.forEach((week) => {
                    const subjectId = week.subjectId;

                    if (!subjectsData[subjectId]) {
                        subjectsData[subjectId] = {
                            label: `Subject ${subjectId}`,
                            data: Array(52).fill(0),
                        };
                    }

                    subjectsData[subjectId].data[week.weekNumber - 1] += week.numberHours * chosenGroups;
                });
            });

            const data = {
                labels: labels,
                datasets: Object.values(subjectsData).map(subject => ({
                    label: "Nombre d'heures par semaine",
                    data: subject.data,
                })),
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
                            title: {
                                display: true,
                                text: "Nombre de semaine",
                            },
                        },
                        y: {
                            type: 'linear',
                            position: 'left',
                            title: {
                                display: true,
                                text: "Nombre d'heures",
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
    }, [associatedWeeks, wishesDetails]);

    function extractGroupId(groupType) {
        const matches = groupType.match(/\/api\/groups\/(\d+)/);
        return matches && matches[1] ? matches[1] : null;
    }

    return (
        <div>
            <div>
                {userWishesFiltered.length > 0 && <canvas id="myChart" width="400" height="100"></canvas>}
            </div>
        </div>
    );
}

export default Graph