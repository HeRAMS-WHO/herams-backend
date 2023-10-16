import { get, post } from './httpMethods';
import { getCsrfToken } from './../utils/csrfTokenUtility';

export const updateProfile = (data) => {
    return post(`/user/profile`, data);
};

export const getRoles = () => {
    const headers = {};
    const csrfToken = getCsrfToken();
    headers['_csrf'] =  csrfToken;
    return get(`/roles/index`, [], headers);
}