import React, { useState } from 'react';
import {Box, IconButton, Menu, MenuItem} from '@mui/material';
import SaveAltIcon from '@mui/icons-material/SaveAlt';
import dayjs from "dayjs";
import languageSelected from "../../../states/languageSelected";
import SnackBarMaterial from "../Alert/SnackBarMaterial";

const ExportOptions = ({ columnDefs, data }) => {
    const [anchorEl, setAnchorEl] = useState(null);
    const [snackbarInfo, setSnackbarInfo] = useState({
        open: false,
        message: '',
        severity: 'success',
        duration: 3000
    });

    const handleClick = (event) => setAnchorEl(event.currentTarget);
    const handleClose = () => setAnchorEl(null);
    const handleSnackbarClose = () => setSnackbarInfo({ ...snackbarInfo, open: false });

    const showSnackbar = (message, severity) => {
        setSnackbarInfo({ open: true, message, severity, duration: 3000 });
    };

    // Separate function for formatting data
    const formatData = (row, field) => {
        let cellValue;
        // Check if the field has i18n data
        console.log(field);
        if (field === "i18n" && row[field] && row[field].title) {
            console.log('test');
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
    };

    const csvExport =  () => {
        try {
            const headers = columnDefs.map(colDef => colDef.headerName);
            const fields = columnDefs.map(colDef => colDef.field);

            // Prepare the CSV content using the formatData function
            const csvContent = [
                headers.join(','), // First line: Header names
                fields.join(','), // Second line: Field names
                ...data.map(row => fields.map(field => formatData(row, field)).join(','))
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

            // Success feedback
            showSnackbar('Data exported successfully!', 'success');
        } catch (error) {
            // Error feedback
            showSnackbar('Failed to export data.', 'error');
        } finally {
            handleClose();
        }
    };

    return (
        <Box position="relative" display="inline-block">
            <IconButton color="primary" size="large" onClick={handleClick}>
                <SaveAltIcon />
                <span>Export</span>
            </IconButton>
            <Menu anchorEl={anchorEl} open={Boolean(anchorEl)} onClose={handleClose}>
                <MenuItem onClick={csvExport}>Export CSV</MenuItem>
            </Menu>
            <SnackBarMaterial
                open={snackbarInfo.open}
                handleClose={handleSnackbarClose}
                message={snackbarInfo.message}
                severity={snackbarInfo.severity}
            />
        </Box>
    );
};

export default ExportOptions;
