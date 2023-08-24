export function decodeProp(encodedProp) {
    console.log('test',encodedProp);
    const decodedJson = atob(encodedProp); // Decode from base64
    return JSON.parse(decodedJson); // Parse the decoded JSON string into a JavaScript object
}
