import { get, post } from './httpMethods';
import { getCsrfToken } from './../utils/csrfTokenUtility';

export const updateProfile = (data) => {
    const csrfToken = getCsrfToken();
    data.append('_csrf', csrfToken);
    return post(`/user/profile`, data);
};

export const getRoles = () => {
    const headers = {};
    const csrfToken = getCsrfToken();
    headers['_csrf'] =  csrfToken;
    return get(`/roles/index`, [], headers);
}