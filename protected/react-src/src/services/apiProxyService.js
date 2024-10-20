import {deleteRequest, get, post, put} from './httpMethods';

//export const BASE_URL = 'http://laravel.herams.test/api';
export const BASE_URL = 'http://api.herams.localhost/api';
//export const BASE_URL = 'https://api-v2.herams-staging.org/api';

export const doLogin = (data) => {
    return post(`${BASE_URL}/authentication/login`, data);
}
export const fetchProfile = (queryParams, headers) => {
    return get(`${BASE_URL}/user/profile`, queryParams, headers);
}

export const fetchLocales = (queryParams, headers) => {
    return get(`${BASE_URL}/configuration/locale?_lang=${languageSelected.value}`, queryParams, headers);
}

export const updateProfile = (data, headers) => {
    return post(`${BASE_URL}/user/profile`, data, headers);
}

export const importWs = (id, data, headers) => {
    return post(`${BASE_URL}/project/${id}/import-ws`, data, headers);
}
export const createRoleAndPermissions = (data) => {
    return post(`${BASE_URL}/role`, data);
}
export const updateRoleAndPermissions = (id, data) => {
    return put(`${BASE_URL}/role/${id}`, data);
}

export const createUserRole = (data) => {
    return post(`${BASE_URL}/user-role`, data);
}
export const fetchCreateWorkspace = (data, headers) => {
    return post(`${BASE_URL}/workspace`, data, headers);
}
export const fetchWorkspace = (id, queryParams, headers) => {
    return get(`${BASE_URL}/workspace/${id}`, queryParams, headers);
}
export const fetchHsdu = (id, queryParams, headers) => {
    return get(`${BASE_URL}/facility/${id}`, queryParams, headers);
}
export const fetchRoles = (queryParams, headers) => {
    return get(`${BASE_URL}/role`, queryParams, headers);
}
export const fetchProject = (id, queryParams, headers) => {
    return get(`${BASE_URL}/project/${id}`, queryParams, headers);
}
export const fetchRolesInProject = (id, queryParams, headers) => {
    return get(`${BASE_URL}/project/${id}/role`, queryParams, headers);
}
export const fetchUsersRolesInWorkspace = (id, queryParams, headers) => {
    return get(`${BASE_URL}/workspace/${id}/user`, queryParams, headers);
}
export const fetchRolesInWorkspace = (id, queryParams, headers) => {
    return get(`${BASE_URL}/workspace/${id}/role`, queryParams, headers);
}
export const fetchProjects = (queryParams, headers) => {
    return get(`${BASE_URL}/project`, queryParams, headers);
}

export const fetchRole = (id, queryParams, headers) => {
    return get(`${BASE_URL}/role/${id}`, queryParams, headers);
}

export const deleteUserRole = (id, queryParams, headers) => {
    return deleteRequest(`${BASE_URL}/user-role/${id}`, queryParams, headers);
}

export const fetchPermissions = (queryParams, headers) => {
    return get(`${BASE_URL}/permission`, queryParams, headers);
}
export const fetchCreateProject = (data, headers) => {
    return post(`${BASE_URL}/project`, data, headers);
}

export const fetchRolePermissions = (id, queryParams, headers) => {
    return get(`${BASE_URL}/role/${id}/permission`, queryParams, headers);
}
export const fetchProjectVisibilityChoices = (data, headers) => {
    const lang = languageSelected.value
    return get(`${BASE_URL}/configuration/project-visibility?_lang=${lang}`, data, headers);
};

export const fetchCountries = (data, headers) => {
    return get(`${BASE_URL}/configuration/country`, data, headers);
};

export const fetchDeleteRole = (id, queryParams, headers) => {
    return deleteRequest(`${BASE_URL}/role/${id}`, queryParams, headers);
}

export const fetchUsers = (queryParams, headers) => {
    return get(`${BASE_URL}/user`, queryParams, headers);
}
export const fetchUser = (id, queryParams, headers) => {
    return get(`${BASE_URL}/user/${id}`, queryParams, headers);
}
export const fetchProjectWorkspaces = (projectId, queryParams, headers) => {
    return get(`${BASE_URL}/project/${projectId}/workspace`, queryParams, headers);
}

export const fetchFacilities = (workspaceId, queryParams, headers) => {
    return get(`${BASE_URL}/workspace/${workspaceId}/HSDU`, queryParams, headers);
}

export const fetchResponses = (hsduId, queryParams, headers) => {
    return get(`${BASE_URL}/HSDU/${hsduId}/data-responses`, queryParams, headers);
}

export const fetchAdminResponses = (hsduId, queryParams, headers) => {
    return get(`${BASE_URL}/HSDU/${hsduId}/admin-responses`, queryParams, headers);
}

export const fetchUserRolesInProject = (projectId, queryParams, headers) => {
    return get(`${BASE_URL}/project/${projectId}/role`, queryParams, headers);
}
export const fetchAUserInformation = (id, queryParams, headers) => {
    return get(`${BASE_URL}/user/${id}`, queryParams, headers);
}

export const fetchUserRoles = (id, queryParams, headers) => {
    return get(`${BASE_URL}/user/${id}/role`, queryParams, headers);
}

export const fetchUpdateWorkspace = ({id, data, headers}) => {
    return post(`${BASE_URL}/workspace/${id}`, data, headers);
}

export const deleteProject = (id, data, headers) => {
    return deleteRequest(`${BASE_URL}/project/${id}`, data, headers);
}
export const deleteSurvey = (id, data, headers) => {
    return deleteRequest(`${BASE_URL}/survey/${id}`, data, headers);
}
export const deleteProjectWorkspaces = (id, data, headers) => {
    return deleteRequest(`${BASE_URL}/project/${id}/delete-workspaces`, data, headers);
}

export const deleteHSDU = (id, data, headers) => {
    return deleteRequest(`${BASE_URL}/facility/${id}`, data, headers);
}

export const fetchCsfrToken = (queryParams, headers) => {
    return get(`${window.location.origin}/token`, queryParams, headers);
}

export const fetchSurveys = (queryParams, headers) => {
    return get(`${BASE_URL}/survey`, queryParams, headers);
}

export const getProjectsMap = (id, queryParams, headers) => {
    return get(`${BASE_URL}/home/projects-map`, queryParams, headers);
}