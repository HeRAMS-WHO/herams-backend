import React, {Component, useEffect, useState} from 'react';
import { __ } from '../../utils/translationsUtility';

const DropdownInput = ({ options = [], value, ...props }) => {
    if (!Array.isArray(options)) {
        console.error("DropdownInput received non-array options:", options);
        options = []; // Reset options to an empty array
    }
    if (options.length === 0) {
        return (<></>);
    }

    return (
        <select value={value} {...props}>
            {!value  && <option value=''>{__('Select an option')}</option>}
            {options.map(option => (
                <option key={option.value} value={option.value}>
                    {option.label}
                </option>
            ))}
        </select>
    );
};


export default DropdownInput;
