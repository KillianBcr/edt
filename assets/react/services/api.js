export const BASE_URL = '/api';

export function fetchSemesters() {
    return fetch(`${BASE_URL}/semesters`).then((response) =>
        response.ok ? response.json() : Promise.resolve(null),
    );
}

export function getSemester(id) {
    return fetch(`${BASE_URL}/semesters/${id}`).then((response) =>
        response.ok ? response.json() : Promise.resolve(null),
    );
}
export function fetchGroups() {
    return fetch(`${BASE_URL}/groups`)
        .then((response) => (response.ok ? response.json() : Promise.resolve(null)));
}

export function getGroup(id) {
    return fetch(`${BASE_URL}/groups/${id}`).then((response) =>
        response.ok ? response.json() : Promise.resolve(null),
    );
}



export const fetchGroupsBySubject = async (subjectId) => {
    try {
        const response = await fetch(`/api/groups?subject=${subjectId}`);
        const data = await response.json();
        return data['hydra:member'];
    } catch (error) {
        console.error('Error in fetchGroupsBySubject:', error);
        throw error;
    }
};
export function getMe()
{
    return fetch(`${BASE_URL}/me`, {credentials: "include"}).then((response) => {
        if (response.ok) {
            return response.json();
        } else if (response.status === 401) {
            return Promise.resolve(null);
        }});
}

export function fetchWishes() {
    return fetch(`${BASE_URL}/wishes`).then((response) =>
        response.ok ? response.json() : Promise.resolve(null),
    );
}

export function getWish(id) {
    return fetch(`${BASE_URL}/wishes/${id}`).then((response) =>
        response.ok ? response.json() : Promise.resolve(null),
    );
}

export function getSubject(id) {
    const isFullUrl = id.startsWith(BASE_URL + '/subjects/');
    const url = isFullUrl ? id : `${BASE_URL}/subjects/${id}`;
    return fetch(url).then((response) =>
        response.ok ? response.json() : Promise.resolve(null),
    );
}
export function fetchNbGroup() {
    return fetch(`${BASE_URL}/nb_groups`).then((response) =>
        response.ok ? response.json() : Promise.resolve(null),
    );
}

export async function getSubjectGroup(id) {
    const isFullUrl = id.startsWith(BASE_URL + '/groups/');
    const url = isFullUrl ? id : `${BASE_URL}/groups/${id}`;
    return fetch(url).then((response) =>
        response.ok ? response.json() : Promise.resolve(null),
    );
}

export async function deleteWish(wishId) {

    const response = await fetch(`/api/wishes/${wishId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
        },
    });

    if (response.ok) {
        return await response.json();
    } else {
        throw new Error('Erreur lors de la suppression du vœu');
    }
}

export function getSubjectTag(id) {
    const isFullUrl = id.startsWith(BASE_URL + '/tags/');
    const url = isFullUrl ? id : `${BASE_URL}/tags/${id}`;
    return fetch(url).then((response) =>
        response.ok ? response.json() : Promise.resolve(null),
    );
}

export function getUserRole(id) {
    return fetch(`${BASE_URL}/users/${id}`, { credentials: "include" })
        .then((response) => {
            if (response.ok) {
                return response.json().then((userData) => {
                    if (userData && userData.roles && userData.roles.includes("ROLE_ADMIN")) {
                        return "ROLE_ADMIN";
                    } else if (userData && userData.roles && userData.roles.includes("ROLE_ENSEIGNANT")) {
                        return "ROLE_ENSEIGNANT";
                    } else {
                        return null;
                    }
                });
            } else if (response.status === 401) {
                return Promise.resolve(null);
            } else {
                console.error('Error fetching user role:', response.status, response.statusText);
                return Promise.reject('Failed to fetch user role');
            }
        })
        .catch((error) => {
            console.error('Error:', error);
        });
}

export async function updateWish(wishId, updatedWishData) {
    const url = `${BASE_URL}/wishes/${wishId}`;

    const response = await fetch(url, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/merge-patch+json',
        },
        body: JSON.stringify(updatedWishData),
    });

    if (response.ok) {
        return await response.json();
    } else {
        throw new Error('Erreur lors de la mise à jour du vœu');
    }
}

export function getLoggedInUserWishes(userId) {
    return fetch(`${BASE_URL}/wishes?userId=${userId}`, { credentials: "include" })
        .then((response) => {
            if (response.ok) {
                return response.json();
            } else {
                return Promise.reject('Failed to fetch wishes for the user');
            }
        })
        .catch((error) => {
            console.error('Error:', error);
        });
}

export function fetchWeeks() {
    return fetch(`${BASE_URL}/weeks`).then((response) =>
        response.ok ? response.json() : Promise.resolve(null),
    );
}

export const fetchWishesForUser = async (userId) => {
    try {
        const response = await fetch(`${BASE_URL}/wishes`, { credentials: "include" });
        const data = await response.json();

        const filteredWishes = data['hydra:member'].filter((wish) => wish.wishUser === `/api/users/${userId}`);

        return filteredWishes;
    } catch (error) {
        console.error('Error in fetchWishesForUser:', error);
        throw error;
    }
};


export const getSubjectName = async (subjectId) => {
    try {
        const response = await getSubject(subjectId);
        return response ? response.name : null;
    } catch (error) {
        console.error('Error in getSubjectName:', error);
        throw error;
    }
};

export const getGroupName = async (groupId) => {
    try {
        const response = await getSubjectGroup(groupId);
        return response ? response.type : null;
    } catch (error) {
        console.error('Error in getGroupName:', error);
        throw error;
    }
};



export function getSubjectCode(id) {
    const isFullUrl = id.startsWith(BASE_URL + '/subject_codes/');
    const url = isFullUrl ? id : `${BASE_URL}/subject_codes/${id}`;

    return fetch(url)
        .then((response) => {
            if (!response.ok) {
                throw new Error(`Failed to fetch subject code: ${response.statusText}`);
            }
            return response.json();
        })
        .then((subjectCodeData) => {
            // Suppose que le code du sujet est dans la propriété "code"
            return subjectCodeData && subjectCodeData.code;
        })
        .catch((error) => {
            console.error('An error occurred while fetching subject code:', error);
            return null; // Vous pouvez ajuster cela en fonction de la logique d'erreur souhaitée
        });
}


export const getCurrentYear = async (subjectId) => {
    try {
        const response = await getSubject(subjectId);

        const currentYear = response?.academicYear?.currentYear;

        if (currentYear !== undefined && currentYear !== null) {
            return currentYear;
        } else {
            console.error('Current year is not defined or null.');
            return null;
        }
    } catch (error) {
        console.error('Error in getCurrentYear:', error);
        throw error;
    }
};

export async function getYearIdWithCurrentYear(apiEndpoint) {
    try {
        const response = await fetch(apiEndpoint);
        const data = await response.json();

        // Find the year with currentYear equal to true
        const yearWithCurrentYear = data['hydra:member'].find((year) => year.currentYear === true);

        if (yearWithCurrentYear) {
            console.log(yearWithCurrentYear.id);
            return yearWithCurrentYear.id;
        } else {
            console.error('No year found with currentYear equal to true');
            return null;
        }
    } catch (error) {
        console.error('Error fetching year data:', error);
        return null;
    }
}

export const getCurrentYearId = async () => {
    try {
        const apiUrl = "/api/years";

        const response = await fetch(apiUrl);
        const data = await response.json();

        const currentYear = data["hydra:member"].find(year => year.currentYear === true);

        if (currentYear) {
            const currentYearId = currentYear.id;
            console.log(currentYearId)
            return currentYearId;
        } else {
            console.error("Aucune année en cours n'a été trouvée.");
            return null;
        }
    } catch (error) {
        console.error('Erreur dans getCurrentYearId:', error);
        throw error;
    }
};

export const getCurrentWishYear = async () => {
    try {
        const apiUrl = "/api/wishes";

        const response = await fetch(apiUrl);
        const data = await response.json();

        console.log("Raw data:", data);

        // Sort wishes based on the 'year' field in descending order
        const sortedWishes = data["hydra:member"].sort((a, b) => b.year - a.year);

        console.log("Sorted wishes:", sortedWishes);

        // Get the 'year' value of the wish with the highest 'year' value
        const currentWishYear = sortedWishes.length > 0 ? sortedWishes[0].year : null;

        console.log("Current wish year:", currentWishYear);

        if (currentWishYear !== null) {
            return currentWishYear;
        } else {
            console.error("Aucun souhait pour l'année en cours n'a été trouvé.");
            return null;
        }
    } catch (error) {
        console.error('Erreur dans getCurrentWishYear:', error);
        throw error;
    }
};






export function addTagToDatabase(tagName) {
    const requestOptions = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ name: tagName }),
    };

    return fetch(`${BASE_URL}/tags`, requestOptions)
        .then((response) => {
            if (response.ok) {
                return response.json();
            }
            return Promise.reject('Failed to add tag to the database');
        })
        .catch((error) => {
            console.error('Error adding tag:', error);
            return Promise.reject('Failed to add tag to the database');
        });
}

export function deleteTagFromDatabase(tagId) {
    const requestOptions = {
        method: 'DELETE',
    };

    return fetch(`${BASE_URL}/tags/${tagId}`, requestOptions)
        .then((response) => {
            if (response.ok) {
                console.log(`Tag with ID ${tagId} deleted successfully`);
            } else {
                console.error('Error deleting tag:', response.status, response.statusText);
                return Promise.reject('Failed to delete tag from the database');
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            return Promise.reject('Failed to delete tag from the database');
        });
}

export function fetchTags() {
    return fetch(`${BASE_URL}/tags`).then((response) =>
        response.ok ? response.json() : Promise.resolve(null),
    );
}

export async function addTagToSubject(tagId, semesterId) {
    try {
        const response = await fetch(`${BASE_URL}/add-tag-to-subject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                tagId: tagId,
                semesterId: semesterId,
            }),
        });

        if (!response.ok) {
            throw new Error('Failed to add tag to subject.');
        }

        const result = await response.json();
        return result;
    } catch (error) {
        throw new Error(`Error adding tag to subject: ${error.message}`);
    }
}

export async function fetchSubjectsForSemester(semestersId) {
    try {
        const response = await fetch(`${BASE_URL}/semesters/${semestersId}`);

        if (!response.ok) {
            throw new Error('Erreur lors de la récupération des matières pour le semestre.');
        }

        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Erreur lors de la récupération des matières pour le semestre :', error);
        throw error;
    }
}
