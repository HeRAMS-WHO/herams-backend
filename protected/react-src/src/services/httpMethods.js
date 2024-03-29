import {getCsrfToken} from "../utils/csrfTokenUtility";
import ValidationError from "../utils/ValidationError";

export const get = async (url, queryParams = {}, headers = {}) => {
    const fullUrl = new URL(url)
    Object.keys(queryParams).forEach(key => fullUrl.searchqueryParams.append(key, queryParams[key]));
    const response = await fetch(fullUrl, {
        method: 'GET',
        headers: {
            'Authorization': 'Bearer ' + sessionStorage?.token,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            ...headers
        },
    });
    return handleResponse(response);
};

export const deleteRequest = async (url, queryParams = {}, headers = {}) => {
    const fullUrl = new URL(url)
    Object.keys(queryParams).forEach(key => fullUrl.searchqueryParams.append(key, queryParams[key]));
    const response = await fetch(fullUrl, {
        method: 'DELETE',
        headers: {
            'Authorization': 'Bearer ' + sessionStorage?.token,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            ...headers
        },
    });
    return handleResponse(response);
};

export const post = async (url, data) => {
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + sessionStorage?.token,
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: JSON.stringify(data),
        });

        if (!response.ok) {
            throw new Error(`Server responded with status: ${response.status}`);
        }

        return response;
    } catch (error) {
        console.error('Error during POST request:', error);
        throw error;  // re-throw the error so it's caught in updateProfile as well
    }
};

export const put = async (url, data) => {
    try {
        const response = await fetch(url, {
            method: 'PUT',
            headers: {
                'Authorization': 'Bearer ' + sessionStorage?.token,
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: JSON.stringify(data),
        });

        if (!response.ok) {
            throw new Error(`Server responded with status: ${response.status}`);
        }

        return response;
    } catch (error) {
        console.error('Error during POST request:', error);
        throw error;  // re-throw the error so it's caught in updateProfile as well
    }
};

export const postUpload = async (url, data) => {
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + sessionStorage?.token,
                //'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: data,
        });

        if (!response.ok) {
          //  throw new Error(`Server responded with status: ${response.status}`);
        }

        return response;
    } catch (error) {
        console.error('Error during POST request:', error);
        throw error;  // re-throw the error so it's caught in updateProfile as well
    }
};

const handleResponse = (response) => {
    if (location.value.includes('login')){
        return [];
    }
    if (!response.ok) {
        throw new Error("Network response was not ok");
    }
    if (response.status === 204) {
        return null;
    }
    return response.json();
};

export const fetchWithCsrf = async  (uri, body = null, method = 'POST') => {
    const response = await fetch(uri, {
        method,
        mode: 'cors',
        cache: 'no-cache',
        credentials: 'same-origin',
        headers: {
            'X-CSRF-Token': getCsrfToken(),
            Accept: 'application/json;indent=2',
            'Accept-Language': document.documentElement.lang ?? 'en',
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + sessionStorage?.token,
        },
        body: (body !== null && typeof body === 'object') ? JSON.stringify(body) : body,
        redirect: 'error',
        referrer: 'no-referrer',
    })

    if (response.status === 422) {
        const json = await response.json()
        throw new ValidationError(json)
    }
    if (response.status === 204) {
        return null
    }

    if (!response.ok) {
        if (response.headers.get('Content-Type').startsWith('application/json')) {
            const content = await response.json()
            throw new Error(`Request failed with code (${response.status}): ${response.statusText}, content: ${content}`)
        } else {
            throw new Error(`Request failed with code (${response.status}): ${response.statusText}`)
        }
    }
    return response.json()
}

/**
 * Sends a POST request to the collection URI. Returns the Location of the created entity
 * @param uri
 * @param body
 * @returns {Promise<void>}
 */
export const createInCollectionWithCsrf = async  (uri, body) => {
    const response = await fetch(uri, {
        method: 'POST',
        mode: 'cors',
        cache: 'no-cache',
        credentials: 'same-origin',
        headers: {
            'X-CSRF-Token': getCsrfToken(),
            Accept: 'application/json;indent=2',
            'Content-Type': 'application/json',
            Authorization: 'Bearer ' + sessionStorage?.token,
        },
        body: JSON.stringify(body),
        redirect: 'error',
        referrer: 'no-referrer',
    })
    const json = await response.json()

    if (response.status === 422) {
        throw new ValidationError(json)
    }
    if (response.status === 201) {
        return json;
    }
    
    if (!response.ok) {
        if (response.headers.get('Content-Type').startsWith('application/json')) {
            const content = await response.json()
            throw new Error(`Request failed with code (${response.status}): ${response.statusText}, content: ${content}`)
        } else {
            throw new Error(`Request failed with code (${response.status}): ${response.statusText}`)
        }
    }
    throw new Error(`Expected status code 201, got ${response.status}`)
}
