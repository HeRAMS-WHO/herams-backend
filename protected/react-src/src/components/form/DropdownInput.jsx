import React, { Component } from 'react';

const DropdownInput = ({ options = [], value, ...props }) => {
    if (!Array.isArray(options)) {
        console.error("DropdownInput received non-array options:", options);
        options = []; // Reset options to an empty array
    }

    return (
        <select value={value} {...props}>
            {options.map(option => (
                <option key={option.value} value={option.value}>
                    {option.label}
                </option>
            ))}
        </select>
    );
};


export default DropdownInput;
