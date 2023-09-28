import React, {useState, useEffect} from 'react';

const CheckboxesGroup = ({options, onChange}) => {
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
        <div className="row">
            {groupOptions.map((parent) => {
                return (
                    <div key={parent}>
                        <h4>{parent}</h4>
                        {options.map((option) => {
                            if (option.parent === parent) {
                                return (
                                    <div key={option.value}>
                                        <input
                                            value={option.value}
                                            type="checkbox"
                                            checked={option.checked}
                                            onChange={onChange} />
                                        <label>{option.label}</label>
                                    </div>
                                );
                            }
                        })}
                    </div>
                );
            })}
        </div>
    );
};

export default CheckboxesGroup;