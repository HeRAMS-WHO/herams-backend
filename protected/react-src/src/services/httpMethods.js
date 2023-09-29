
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

export const post = async (url, data) => {
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',  // Ensure you have the correct content type
            },
            body: data,
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

export const postYii = (url, data) => {
    return $.post(url, data, function (response) {
        return response;
    })
}

const handleResponse = (response) => {
    if (!response.ok) {
        throw new Error("Network response was not ok");
    }
    return response.json();
};
