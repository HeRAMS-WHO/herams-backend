import {get, post, postForm} from './httpMethods';

export const BASE_URL = window.HERAMS_PROXY_API_URL || `${window.location.origin}/api-proxy/core`;

export const fetchProfile = (params, headers) => {
    return get(`${BASE_URL}/user/profile`, params, headers);
};

export const updateProfile = (data, headers) => {
    return postForm(`${BASE_URL}/user/profile`, data, headers);
};

export const updateRoleAndPermissions = (id, data) => {
    return post(`${BASE_URL}/roles/${id}/update`, data);
}
export const fetchRoles = (params, headers) => {
    return get(`${BASE_URL}/roles/index`, params, headers);
}
export const fetchProjects = (params, headers) => {
    return get(`${BASE_URL}/projects`, params, headers);
}

export const fetchRole = (id, params, headers) => {
    return get(`${BASE_URL}/roles/${id}/view`, params, headers);
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
