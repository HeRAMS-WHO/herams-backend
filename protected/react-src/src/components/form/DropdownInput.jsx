import React, { Component } from 'react';

class DropdownInput extends Component {
    handleChange = (e) => {
        this.props.onChange(e.target.value);
    }

    render() {
        const { name, options, value } = this.props;
        return (
            <select name={name} value={value} onChange={this.handleChange}>
                {options.map((option, index) => (
                    <option key={index} value={option.value}>
                        {option.label}
                    </option>
                ))}
            </select>
        );
    }
}

export default DropdownInput;
