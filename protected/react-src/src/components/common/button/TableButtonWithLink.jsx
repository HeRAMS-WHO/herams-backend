import React from 'react';
import PropTypes from 'prop-types';

const TableButtonWithLInk = ({ buttons = [] }) => {
    return (
        <div className={'ButtonGroup'}>
            {buttons.map((button, index) => (
                <Link key={index} to={button.url} className={`btn ${button.class}`}>
                    {button.icon && <span className="material-icons">{button.icon}</span>}
                    {button.label}
                </Link>
            ))}
        </div>
    );
}

TableButtonWithLInk.propTypes = {
    buttons: PropTypes.arrayOf(
        PropTypes.shape({
            label: PropTypes.string.isRequired,
            url: PropTypes.string.isRequired,
            class: PropTypes.string,
            icon: PropTypes.string
        })
    )
};

export default TableButtonWithLInk;
