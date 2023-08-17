import React, { Component } from 'react';

class CheckboxInput extends Component {
    handleChange = (e) => {
        this.props.onChange(e.target.checked);
    }

    render() {
        const { name, checked } = this.props;
        return (
            <input
                type="checkbox"
                name={name}
                checked={checked}
                onChange={this.handleChange}
            />
        );
    }
}

export default CheckboxInput;
