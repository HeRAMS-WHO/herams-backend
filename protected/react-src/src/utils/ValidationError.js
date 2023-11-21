class ValidationError extends Error {
    errors = {}

    constructor(errors) {
        super('Validation failed');
        this.errors = errors;
    }
}

export default ValidationError;
