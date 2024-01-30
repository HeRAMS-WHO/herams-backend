import React, { useState } from 'react'
import { Button, IconButton, Menu, MenuItem } from '@mui/material';
import * as FileSaver from 'file-saver';
import * as XLSX from 'xlsx';
import SaveAltIcon from '@mui/icons-material/SaveAlt';

const ExportOptions = ({ columnDefs, data }) => {

  const [anchorEl, setAnchorEl] = useState(null);
  const open = Boolean(anchorEl);

  const handleClick = (event) => {
    setAnchorEl(event.currentTarget);
  };

  const handleClose = () => {
    setAnchorEl(null);
  };

  const xlsxExport = () => {
    const headers = columnDefs.map(colDef => colDef.headerName);
    const fields = columnDefs.map(colDef => colDef.field);
    const exportData = [headers, fields];

    data.forEach(row => {
      const rowData = columnDefs.map(colDef => row[colDef.field]);
      exportData.push(rowData);
    });

    const worksheet = XLSX.utils.aoa_to_sheet(exportData);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet1');
    const excelBuffer = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
    const dataBlob = new Blob([excelBuffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8' });
    FileSaver.saveAs(dataBlob, 'exportedData.xlsx');

    handleClose(); // Close the menu after export
  };


  return (
    <div>
      <IconButton color="primary"  size="large" onClick={handleClick}>
        <span className="export-icon-container">
          <SaveAltIcon />
          <span className="export-text">Export</span>
        </span>
      </IconButton>

      <Menu anchorEl={anchorEl} open={Boolean(anchorEl)} onClose={handleClose}>
        <MenuItem onClick={xlsxExport}>Export XLSX</MenuItem>
        {/* Add more menu items for other exports if needed */}
      </Menu>
    </div>
  );
};

export default ExportOptions;
