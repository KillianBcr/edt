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



export function fetchGroupsBySubject(subjectId) {
    return fetch(`${BASE_URL}/groups?subject=${subjectId}`)
        .then((response) => {
            if (response.ok) {
                return response.json();
            }
            return Promise.reject('Failed to fetch groups');
        });
}
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

export function getSubjectGroup(id) {
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
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(updatedWishData),
    });

    if (response.ok) {
        return await response.json();
    } else {
        throw new Error('Erreur lors de la mise à jour du vœu');
    }
}

export async function WishesBySubject() {
    const allWishes = await fetchWishes();
    const wishesBySubject = {};

    for (const wish of allWishes) {
        const subjectId = wish.subjectId;
        if (!wishesBySubject[subjectId]) {
            wishesBySubject[subjectId] = {};
        }

        const groups = await fetchGroupsBySubject(subjectId);
        for (const group of groups) {
            const groupId = group.id;
            if (!wishesBySubject[subjectId][groupId]) {
                wishesBySubject[subjectId][groupId] = 0;
            }
            wishesBySubject[subjectId][groupId]++;
        }
    }

    return wishesBySubject;
}

