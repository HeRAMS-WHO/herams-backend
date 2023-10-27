import {getCsrfToken} from "../utils/csrfTokenUtility";

export const get = async (url, params = {}, headers = {}) => {
    const fullUrl = new URL(url)
    Object.keys(params).forEach(key => fullUrl.searchParams.append(key, params[key]));
    const response = await fetch(fullUrl, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Csrf-Token': getCsrfToken(),
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
                'Content-Type': 'application/json',
                'X-Csrf-Token': getCsrfToken(),
                'X-Requested-With': 'XMLHttpRequest'
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

const handleResponse = (response) => {
    if (!response.ok) {
        throw new Error("Network response was not ok");
    }
    return response.json();
};
