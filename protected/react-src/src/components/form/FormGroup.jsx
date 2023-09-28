const FormGroup = ({ label, error, children }) => (
    <div className="form-group highlight-addon row">
        {label && <label className="control-label has-star col-md-3 ">{label}</label>}
        <div className='col-md-9'>{children}</div>
        {error && <div className="help-block help-block-error">{error}</div>}
    </div>
);
export default FormGroup;