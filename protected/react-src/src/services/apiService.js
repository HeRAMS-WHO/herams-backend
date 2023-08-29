import { get, post } from './httpMethods';
import { getCsrfToken } from './../utils/csrfTokenUtility';

export const updateProfile = (data) => {
    const csrfToken = getCsrfToken();
    data.append('_csrf', csrfToken);
    return post(`/user/profile`, data);
};
