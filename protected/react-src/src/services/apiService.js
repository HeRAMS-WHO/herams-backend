import { get, post, postUpload } from './httpMethods';
import { getCsrfToken } from './../utils/csrfTokenUtility';
import {BASE_URL} from "./apiProxyService";

export const updateProfile = (data) => {
    return post(`/user/profile`, data);
};

export const importWs = (id, data) => {
    return postUpload(BASE_URL + `/project/${id}/import-ws`, data);
};

export const getRoles = () => {
    const headers = {};
    const csrfToken = getCsrfToken();
    headers['_csrf'] =  csrfToken;
    return get(`/roles/index`, [], headers);
}