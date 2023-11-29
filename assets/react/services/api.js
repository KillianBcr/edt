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
        throw error; // Ajoute cette ligne pour propager l'erreur
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


export const getSubjectYear = async (subjectId) => {
    try {
        const response = await getSubject(subjectId);
        return response ? response.academicYear.currentYear : null;
    } catch (error) {
        console.error('Error in getSubjectName:', error);
        throw error;
    }
};


