import {deleteRequest, get, post} from './httpMethods';

export const BASE_URL = window.HERAMS_PROXY_API_URL || `${window.location.origin}/api-proxy/core`;

export const fetchProfile = (params, headers) => {
    return get(`${BASE_URL}/user/profile`, params, headers);
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

export const fetchRoles = (params, headers) => {
    return get(`${BASE_URL}/roles/index`, params, headers);
}

export const fetchRolesInProject = (id, params, headers) => {
    return get(`${BASE_URL}/project/${id}/roles`, params, headers);
}

export const fetchProjects = (params, headers) => {
    return get(`${BASE_URL}/projects`, params, headers);
}

export const fetchRole = (id, params, headers) => {
    return get(`${BASE_URL}/roles/${id}/view`, params, headers);
}

export const deleteUserRole = (id, params, headers) => {
    return deleteRequest(`${BASE_URL}/user-role/${id}`, params, headers);
}

export const fetchPermissions = (params, headers) => {
    return get(`${BASE_URL}/permissions/index`, params, headers);
}

export const fetchRolePermissions = (id, params, headers) => {
    return get(`${BASE_URL}/roles/${id}/permissions`, params, headers);
}
export const fetchProjectVisibilityChoices = (data, headers) => {
    const lang = document.documentElement.lang;
    return get(`${BASE_URL}/configuration/visibilities?_lang=${lang}`, data, headers);
};

export const fetchDeleteRole = (id, params, headers) => {
    return get(`${BASE_URL}/roles/${id}/delete`, params, headers);
}

export const fetchUsers = (params, headers) => {
    return get(`${BASE_URL}/user/index`, params, headers);
}

export const fetchProjectWorkspaces = (projectId, params, headers) => {
    return get(`${BASE_URL}/project/${projectId}/workspaces`, params, headers);
}

export const fetchUserRolesInProject = (projectId, params, headers) => {
    return get(`${BASE_URL}/user-role/project/${projectId}/index`, params, headers);
}