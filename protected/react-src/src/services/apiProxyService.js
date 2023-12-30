import { param } from 'jquery';
import {deleteRequest, get, post} from './httpMethods';

export const BASE_URL = window.HERAMS_PROXY_API_URL || `${window.location.origin}/api-proxy/core`;

export const fetchProfile = (queryParams, headers) => {
    return get(`${BASE_URL}/user/profile`, queryParams, headers);
}

export const fetchLocales = (queryParams, headers) => {
    return get(`${BASE_URL}/configuration/locales?_lang=${languageSelected.value}`, queryParams, headers);
}

export const updateProfile = (data, headers) => {
    return post(`${BASE_URL}/user/profile`, data, headers);
}

export const updateRoleAndPermissions = (id, data) => {
    return post(`${BASE_URL}/roles/${id}/update`, data);
}

export const createUserRole = (data) => {
    return post(`${BASE_URL}/user-role`, data);
}
export const fetchWorkspace = (id, queryParams, headers) => {
    return get(`${BASE_URL}/workspace/${id}/view`, queryParams, headers);
}
export const fetchHsdu = (id, queryParams, headers) => {
    return get(`${BASE_URL}/facility/${id}/view`, queryParams, headers);
}
export const fetchRoles = (queryParams, headers) => {
    return get(`${BASE_URL}/roles/index`, queryParams, headers);
}
export const fetchProject = (id, queryParams, headers) => {
    return get(`${BASE_URL}/project/${id}/view`, queryParams, headers);
}
export const fetchRolesInProject = (id, queryParams, headers) => {
    return get(`${BASE_URL}/project/${id}/roles`, queryParams, headers);
}
export const fetchUsersRolesInWorkspace = (id, queryParams, headers) => {
    return get(`${BASE_URL}/workspace/${id}/users`, queryParams, headers);
}
export const fetchRolesInWorkspace = (id, queryParams, headers) => {
    return get(`${BASE_URL}/workspace/${id}/roles`, queryParams, headers);
}
export const fetchProjects = (queryParams, headers) => {
    return get(`${BASE_URL}/projects`, queryParams, headers);
}

export const fetchRole = (id, queryParams, headers) => {
    return get(`${BASE_URL}/roles/${id}/view`, queryParams, headers);
}

export const deleteUserRole = (id, queryParams, headers) => {
    return deleteRequest(`${BASE_URL}/user-role/${id}`, queryParams, headers);
}

export const fetchPermissions = (queryParams, headers) => {
    return get(`${BASE_URL}/permissions/index`, queryParams, headers);
}

export const fetchRolePermissions = (id, queryParams, headers) => {
    return get(`${BASE_URL}/roles/${id}/permissions`, queryParams, headers);
}
export const fetchProjectVisibilityChoices = (data, headers) => {
    const lang = document.documentElement.lang;
    return get(`${BASE_URL}/configuration/visibilities?_lang=${lang}`, data, headers);
};

export const fetchDeleteRole = (id, queryParams, headers) => {
    return get(`${BASE_URL}/roles/${id}/delete`, queryParams, headers);
}

export const fetchUsers = (queryParams, headers) => {
    return get(`${BASE_URL}/user/index`, queryParams, headers);
}
export const fetchUser = (id, queryParams, headers) => {
    return get(`${BASE_URL}/user/${id}/view`, queryParams, headers);
}
export const fetchProjectWorkspaces = (projectId, queryParams, headers) => {
    return get(`${BASE_URL}/project/${projectId}/workspaces`, queryParams, headers);
}

export const fetchFacilities = (workspaceId, queryParams, headers) => {
    return get(`${BASE_URL}/workspace/${workspaceId}/facilities`, queryParams, headers);
}

export const fetchResponses = (hsduId, queryParams, headers) => {
    return get(`${BASE_URL}/facility/${hsduId}/data-responses`, queryParams, headers);
}

export const fetchAdminResponses = (hsduId, queryParams, headers) => {
    return get(`${BASE_URL}/facility/${hsduId}/admin-responses`, queryParams, headers);
}

export const fetchUserRolesInProject = (projectId, queryParams, headers) => {
    return get(`${BASE_URL}/user-role/project/${projectId}/index`, queryParams, headers);
}
export const fetchAUserInformation = (id, queryParams, headers) => {
    return get(`${BASE_URL}/user/${id}/view`, queryParams, headers);
}

export const fetchUserRoles = (id, queryParams, headers) => {
    return get(`${BASE_URL}/user/${id}/roles`, queryParams, headers);
}

export const fetchUpdateWorkspace = ({id, data, headers}) => {
    return post(`${BASE_URL}/workspace/${id}`, data, headers);
}

export const deleteProject = (id, data, headers) => {
    return post(`${BASE_URL}/project/${id}/delete-project`, data, headers);
}

export const deleteProjectWorkspaces = (id, data, headers) => {
    return post(`${BASE_URL}/project/${id}/delete-workspaces`, data, headers);
}

export const fetchCsfrToken = (queryParams, headers) => {
    return get(`${window.location.origin}/token`, queryParams, headers);
}

export const fetchSurveys = (queryParams, headers) => {
    return get(`${BASE_URL}/surveys`, queryParams, headers);
}
