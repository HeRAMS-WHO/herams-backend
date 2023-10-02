import { get, post } from './httpMethods';

const BASE_URL = window.HERAMS_PROXY_API_URL || `${window.location.origin}/api-proxy/core`;

export const fetchProfile = (params, headers) => {
    return get(`${BASE_URL}/user/profile`, params, headers);
};

export const updateProfile = (data, headers) => {
    return post(`${BASE_URL}/user/profile`, data, headers);
};

export const fetchProjectVisibilityChoices = (data, headers) => {
    const lang = document.documentElement.lang;
    return get(`${BASE_URL}/configuration/visibilities?_lang=${lang}`, data, headers);
};
