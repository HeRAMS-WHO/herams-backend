import React, {useState} from 'react';
import {IconButton, Menu, MenuItem} from '@mui/material';
import SaveAltIcon from '@mui/icons-material/SaveAlt';
import dayjs from "dayjs";
import languageSelected from "../../../states/languageSelected";

const ExportOptions = ({columnDefs, data}) => {
    const [anchorEl, setAnchorEl] = useState(null);

    const handleClick = (event) => {
        setAnchorEl(event.currentTarget);
    };

    const handleClose = () => {
        setAnchorEl(null);
    };

    const csvExport = () => {
        const headers = columnDefs.map(colDef => colDef.headerName);
        const fields = columnDefs.map(colDef => colDef.field);

        // Prepare the CSV content
        const csvContent = [
            headers.join(','), // First line: Header names
            fields.join(','), // Second line: Field names
            // Following lines: Data rows
            ...data.map(row =>
                fields.map(field => {
                    let cellValue;
                    // Check if the field has i18n data
                    if (field === "i18n" && row[field] && row[field].title) {
                        const i18nValue = row[field].title;
                        // Fetch the value based on the selected language, fallback to 'en' if not available
                        cellValue = i18nValue[languageSelected.value] || i18nValue['en'];
                    } else {
                        // Handle non-i18n fields, including date formatting
                        cellValue = row[field];
                        if (columnDefs.find(colDef => colDef.field === field && colDef.type === 'date')) {
                            cellValue = cellValue ? dayjs(cellValue).format('YYYY-MM-DD') : '';
                        } else if (cellValue === undefined || typeof cellValue === 'object') {
                            cellValue = ''; // Exclude actions or handle other complex objects
                        }
                    }
                    // Escape quotes and wrap in quotes if necessary
                    return `"${String(cellValue).replace(/"/g, '""')}"`;
                }).join(',')
            )
        ].join('\r\n');

        // Create and trigger a download of the CSV file
        const blob = new Blob([csvContent], {type: 'text/csv;charset=utf-8;'});
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.setAttribute('href', url);
        link.setAttribute('download', 'exportedData.csv');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    };


    return (
        <div>
            <IconButton color="primary" size="large" onClick={handleClick}>
                <SaveAltIcon/>
                <span>Export</span>
            </IconButton>
            <Menu anchorEl={anchorEl} open={Boolean(anchorEl)} onClose={handleClose}>
                <MenuItem onClick={csvExport}>Export CSV</MenuItem>
                {/* Add more menu items for other exports if needed */}
            </Menu>
        </div>
    );
};

export default ExportOptions;
