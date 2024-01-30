// CustomToolbar.jsx
import React from 'react';
import { GridToolbarContainer, GridToolbarFilterButton } from '@mui/x-data-grid';
import ExportOptions from './ExportOptions';

const CustomToolbar = ({columnDefs, data}) => {
  return (
    <GridToolbarContainer>
      <GridToolbarFilterButton />
      <ExportOptions columnDefs={columnDefs} data={data} />
    </GridToolbarContainer>
  );
};

export default CustomToolbar;
