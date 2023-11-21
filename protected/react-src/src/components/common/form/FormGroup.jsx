import React from 'react';
import './FormGroup.css'

const FormGroup = ({ label, hasStar = false, inputClassName = 'form-group col-md-5', error, children }) => {
    return (<div className="form-group highlight-addon row mt-2 d-flex" >
        <div className='form-group col-md-3 d-flex align-items-center'>
            {label && <label className={`control-label ${hasStar ? 'has-star' : ''}`}>{label}</label>}
        </div>
        <div className={`${inputClassName} d-flex align-items-center`}>
            {children}
        </div>
        {error && <div className="col-12 help-block help-block-error">{error}</div>}
    </div>)
}
export default FormGroup;
