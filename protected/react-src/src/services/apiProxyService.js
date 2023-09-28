import { get, post } from './httpMethods';

const BASE_URL = window.HERAMS_PROXY_API_URL || `${window.location.origin}/api-proxy/core`;

export const fetchProfile = (params, headers) => {
    return get(`${BASE_URL}/user/profile`, params, headers);
};

export const updateProfile = (data, headers) => {
    return post(`${BASE_URL}/user/profile`, data, headers);
};

export const fetchRoles = (params, headers) => {
    return get(`${BASE_URL}/roles/index`, params, headers);
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