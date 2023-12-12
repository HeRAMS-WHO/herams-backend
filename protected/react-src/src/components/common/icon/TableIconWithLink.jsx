import React from 'react';
import PropTypes from 'prop-types';
import IconButton from '@mui/material/IconButton';

const TableIconWithLink = ({ icons = [] }) => {
    return (
        <div className='d-flex'>
            {icons.map((icon, index) => (
                <IconButton
                    key={index}
                    component={Link}
                    to={icon.url}
                    style={{ color: 'black' }} // Inline style for black color
                >
                    <span className="material-icons">{icon.iconName}</span>
                </IconButton>
            ))}
        </div>
    );
}

TableIconWithLink.propTypes = {
    icons: PropTypes.arrayOf(
        PropTypes.shape({
            url: PropTypes.string.isRequired,
            iconName: PropTypes.string.isRequired,
            class: PropTypes.string
        })
    )
};

export default TableIconWithLink;
