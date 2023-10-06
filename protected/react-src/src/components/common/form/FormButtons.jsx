import React from 'react';
import PropTypes from 'prop-types';

function FormButtons({ buttons = [] }) {
    return (
        <div className={'ButtonGroup'}>
            {buttons.map((button, index) => (
                <button key={index} type="button" className={button.class} onClick={button.onClick}>
                    {button.label}
                </button>
            ))}
        </div>
    );
}

FormButtons.propTypes = {
    buttons: PropTypes.arrayOf(
        PropTypes.shape({
            label: PropTypes.string.isRequired,
            class: PropTypes.string,
            onClick: PropTypes.func.isRequired
        })
    )
};

export default FormButtons;
