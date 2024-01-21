import React from 'react';
import { GridToolbarContainer, GridToolbarExport, GridToolbarFilterButton } from '@mui/x-data-grid';

const CustomToolbar = () => {
  return (
    // eslint-disable-next-line react/jsx-no-undef
    <GridToolbarContainer>
      <GridToolbarFilterButton/>
      <GridToolbarExport/>
    </GridToolbarContainer>
  )
}
export default CustomToolbar
