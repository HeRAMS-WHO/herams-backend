import React, {useState, useEffect} from 'react';

const CheckboxesGroup = ({options, onChange, changeChildren}) => {
    const [groupOptions, setGroupOptions] = useState([]);
    useEffect(() => {
        const checkboxesForGrouping = [];
        options.forEach((option) => {
            if (checkboxesForGrouping.includes(option.parent) === false) {
                checkboxesForGrouping.push(option.parent);
            }
        })
        setGroupOptions(checkboxesForGrouping.sort((a, b) => a.localeCompare(b, 'en', {sensitivity: 'base'})));
    }, [options]);
    return (
        <>
            {groupOptions.map((parent) => {
                return (
                    <div key={parent} className="form-horizontal form-group align-baseline w-90">
                        <div className='form-group col-md-3'>
                            <input
                                onChange={e => changeChildren(parent, e.target.checked)}
                                type="checkbox"/>
                            <label className="control-label" >{parent}</label>
                        </div>
                        <div className='form-group col-md-5'>
                            {options.map((option) => {
                                if (option.parent === parent) {
                                    return (
                                        <div key={option.value}>
                                            <input
                                                value={option.value}
                                                type="checkbox"
                                                checked={option.checked}
                                                onChange={e => onChange(option.value, !option.checked)} />
                                            <label style={{fontStyle:'14px', marginLeft:'4px'}}>{option.label}</label>
                                        </div>
                                    );
                                }
                            })}
                        </div>
                    </div>
                );
            })}
        </>
    );
};

export default CheckboxesGroup;