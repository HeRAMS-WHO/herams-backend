import {getCsrfToken} from "../utils/csrfTokenUtility";

export const get = async (url, params = {}, headers = {}) => {
    const fullUrl = new URL(url)
    Object.keys(params).forEach(key => fullUrl.searchParams.append(key, params[key]));
    const response = await fetch(fullUrl, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            ...headers
        },
    });
    return handleResponse(response);
};

const convertDataToFormUrlEncoded = (data, parentKey = '') => {
    const form = new URLSearchParams();
    for (const key in data) {
        const newKey = parentKey ? `${parentKey}[${key}]` : key;

        if (typeof data[key] === 'object') {
            const nestedFormData = convertDataToFormUrlEncoded(data[key], newKey);
            for (const [nestedKey, nestedValue] of nestedFormData) {
                form.append(nestedKey, nestedValue);
            }
        } else if (Array.isArray(data[key])) {
            for (let i = 0; i < data[key].length; i++) {
                const nestedFormData = convertDataToFormUrlEncoded(
                    { [i]: data[key][i] },
                    `${newKey}`
                );
                for (const [nestedKey, nestedValue] of nestedFormData) {
                    form.append(nestedKey, nestedValue);
                }
            }
        } else {
            form.append(newKey, data[key]);
        }
    }
    return form;
};
export const post = async (url, data) => {
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                'X-Csrf-Token': getCsrfToken(),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: convertDataToFormUrlEncoded(data),
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
