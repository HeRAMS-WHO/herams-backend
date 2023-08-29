/**
 * Transforms an object's key-value pairs into an array of {value, label} format.
 *
 * @param {object} obj - The object to be transformed.
 * @returns {Array} An array of {value, label} objects.
 */
export function formatObjectToValueLabel(obj) {
    return Object.entries(obj).map(([value, label]) => ({ value, label }));
}
