import React from 'react';
import './FormGroup.css'

const FormGroup = ({ label, hasStar = false, error, children }) => {
    return (<div className="form-group highlight-addon row" >
        <div className='form-group col-md-3'>
            {label && <label className={`control-label ${hasStar ? 'has-star' : ''}`}>{label}</label>}
        </div>
        <div className='form-group col-md-5'>
            {children}
        </div>
        {error && <div className="col-12 help-block help-block-error">{error}</div>}
    </div>)
}
export default FormGroup;
