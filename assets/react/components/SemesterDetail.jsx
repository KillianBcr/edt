import React, { useState, useEffect } from 'react';
import { getSemester, fetchNbGroup, fetchGroups, getMe, getSubjectCode } from '../services/api';
import { useRoute } from 'wouter';
import WishForm from './WishForm';
import "../../styles/semesterDetail.css";

function Semester() {
    const [semester, setSemester] = useState(null);
    const [, params] = useRoute('/react/semesters/:id');
    const [userData, setUserData] = useState(null);
    const [groups, setGroups] = useState([]);
    const [nbGroups, setNbGroups] = useState([]);
    const [wishesBySubject, setWishesBySubject] = useState({});
    const [subjectCodes, setSubjectCodes] = useState([]);

    useEffect(() => {
        (async () => {
            try {
                const fetchedGroupsResponse = await fetchGroups();
                //console.log('Fetched Groups:', fetchedGroupsResponse);

                // Assure-toi que les données sont bien encapsulées dans un objet
                const fetchedGroups = fetchedGroupsResponse['hydra:member'] || [];
                setGroups(fetchedGroups);

                const groupData = await fetchNbGroup();
                if (Array.isArray(groupData['hydra:member'])) {
                    setNbGroups(groupData['hydra:member']);
                } else if (Array.isArray(groupData.nbGroups)) {
                    setNbGroups(groupData.nbGroups);
                } else {
                    console.error("Data from API is not an array:", groupData);
                }
            } catch (error) {
                console.error("An error occurred while fetching groups:", error);
            }
        })();
    }, []);

    useEffect(() => {
        getSemester(params.id).then((data) => {
            setSemester(data);
        });
        getMe().then((userData) => {
            setUserData(userData);
        });
    }, [params.id]);

    const getWishesCountBySubject = async () => {
        try {
            const allWishesResponse = await fetch('/api/wishes');
            if (!allWishesResponse.ok) {
                throw new Error('La requête pour les souhaits a échoué.');
            }

            const allWishes = await allWishesResponse.json();
            const wishesBySubjectData = {};

            if (Array.isArray(allWishes['hydra:member'])) {
                for (const wish of allWishes['hydra:member']) {
                    const groupeType = wish.groupeType;
                    const chosenGroups = wish.chosenGroups || 0;

                    if (!wishesBySubjectData[groupeType]) {
                        wishesBySubjectData[groupeType] = chosenGroups;
                    } else {
                        wishesBySubjectData[groupeType] += chosenGroups;
                    }
                }

                setWishesBySubject(wishesBySubjectData);
            } else {
                console.error("hydra:member n'est pas un tableau :", allWishes['hydra:member']);
            }
        } catch (error) {
            console.error("Une erreur s'est produite lors du traitement des souhaits :", error);
        }
    };

    useEffect(() => {
        if (semester !== null) {
            getWishesCountBySubject()
                .then(() => {})
                .catch(error => {
                    console.error("Une erreur s'est produite :", error);
                });
        }
    }, [semester]);

    const fetchWishesAndUpdateCount = async () => {
        try {
            const allWishesResponse = await fetch('/api/wishes');
            if (!allWishesResponse.ok) {
                throw new Error('La requête pour les souhaits a échoué.');
            }

            const allWishes = await allWishesResponse.json();
            const wishesBySubjectData = {};

            if (Array.isArray(allWishes['hydra:member'])) {
                for (const wish of allWishes['hydra:member']) {
                    const groupeType = wish.groupeType;
                    const chosenGroups = wish.chosenGroups || 0;

                    if (!wishesBySubjectData[groupeType]) {
                        wishesBySubjectData[groupeType] = chosenGroups;
                    } else {
                        wishesBySubjectData[groupeType] += chosenGroups;
                    }
                }

                setWishesBySubject(wishesBySubjectData);
            } else {
                console.error("hydra:member n'est pas un tableau :", allWishes['hydra:member']);
            }
        } catch (error) {
            console.error("Une erreur s'est produite lors du traitement des souhaits :", error);
        }
    };

    const fetchYearData = async (yearLink) => {
        try {
            const response = await fetch(yearLink);
            if (!response.ok) {
                throw new Error(`La requête pour l'année a échoué : ${response.statusText}`);
            }
            const yearData = await response.json();
            return yearData;
        } catch (error) {
            console.error("Erreur lors de la récupération des données de l'année :", error);
            throw error;
        }
    };

    const isCurrentYear = async (yearLink) => {
        try {
            const yearData = await fetchYearData(yearLink);
            return yearData.currentYear === true;
        } catch (error) {
            console.warn("Erreur lors de la récupération des données de l'année :", error);
            return false;
        }
    };

    async function fetchSubjectCodes() {
        try {
            const response = await fetch('/api/subject_codes');
            if (!response.ok) {
                throw new Error(`Failed to fetch subject codes: ${response.statusText}`);
            }

            const subjectCodesData = await response.json();
            console.log('Subject Codes:', subjectCodesData);

            if (Array.isArray(subjectCodesData['hydra:member'])) {
                return subjectCodesData['hydra:member'];
            } else {
                console.error("Data from API is not an array:", subjectCodesData);
                return [];
            }
        } catch (error) {
            console.error("An error occurred while fetching subject codes:", error);
            return [];
        }
    }

    useEffect(() => {
        if (semester) {
            Promise.all(semester.subjects.map(async (subject) => {
                const subjectCodeData = await fetchSubjectCodeDetails(subject.subjectCode);
                const subjectCode = subjectCodeData.code;
                return { ...subject, subjectCode };
            })).then(updatedSubjects => {
                setSemester(prevSemester => ({
                    ...prevSemester,
                    subjects: updatedSubjects
                }));
            });
        }
    }, [semester]);

    return (
        <div>
            {semester === null ? 'Loading...' : (
                <div className={"subjectList"}>
                    <ul>
                        {semester.subjects.map((subject) => {
                            const subjectId = subject['@id'].split('/').pop();
                            const resolvedSubjectCodeId = subject.subjectCode.code;


                            if (isCurrentYear(subject.academicYear)) {
                                console.log('Displaying subject for the current year:', subject.name);
                                return (
                                    <li key={subject['@id']} className="semester-li">
                                        <h2 className={"subjectName"}>{resolvedSubjectCodeId + ' - ' + subject.name}</h2>
                                        {(userData && userData.roles && (userData.roles.includes("ROLE_ADMIN") || userData.roles.includes("ROLE_ENSEIGNANT"))) ? (
                                            <div>
                                                <div className="groupe-container">
                                                    {groups === null ? 'Aucun Groupe Trouvé' : (
                                                        groups.filter((group) => group.subject === subject['@id'])
                                                            .map((group) => (
                                                                <ul key={group.id}>
                                                                    <li className="groups">
                                                                        {group.type}
                                                                        {nbGroups === null ? (
                                                                            'Aucun Nombre De Groupe Trouvé'
                                                                        ) : (
                                                                            nbGroups
                                                                                .filter((nbGroup) => nbGroup.groups.includes(`/api/groups/${group.id}`))
                                                                                .map((filteredNbGroup) => {
                                                                                    if (filteredNbGroup.nbGroup === 0 || filteredNbGroup.nbGroup === null) {
                                                                                        return null;
                                                                                    } else {
                                                                                        const groupId = (typeof filteredNbGroup.groups === 'string') ? filteredNbGroup.groups.split('/').pop() : filteredNbGroup.groups;
                                                                                        const count = wishesBySubject && wishesBySubject[groupId] ? wishesBySubject[groupId] : 0;
                                                                                        return (
                                                                                            <span
                                                                                                key={`${filteredNbGroup.id}`}>| {count}/{filteredNbGroup.nbGroup}</span>
                                                                                        );
                                                                                    }
                                                                                })
                                                                        )}
                                                                    </li>
                                                                </ul>
                                                            ))
                                                    )}
                                                </div>
                                                <div className="Postuler-container">
                                                    {userData && (
                                                        <WishForm subjectId={`/api/subjects/${subjectId}`}
                                                                  onWishAdded={fetchWishesAndUpdateCount}
                                                                  userData={userData}/>
                                                    )}
                                                </div>
                                            </div>
                                        ) : null}
                                    </li>
                                );
                            } else {
                                console.log('Skipping subject for a different year:', subject.name);
                            }
                            return null;
                        })}
                    </ul>
                </div>
            )}
        </div>
    );
}

export default Semester;
