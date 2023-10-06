// MockDropdown.jsx
import React from 'react';

const MockDropdown = ({ choices, onChange }) => {
    return (
        <select onChange={onChange}>
            {choices.map(choice => (
                <option key={choice} value={choice}>{choice}</option>
            ))}
        </select>
    );
};

export default MockDropdown;
