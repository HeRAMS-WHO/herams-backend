function flattenJSONRoutes(json, result = []) {
    const {children, ...rest} = json;
    result.push(rest);
    children.forEach(child => {
        flattenJSONRoutes(child, result);
    });
    return result;
}
export default flattenJSONRoutes;