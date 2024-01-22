import React, { useCallback, useMemo, useRef } from 'react';
import {
  DataGrid,
} from '@mui/x-data-grid';

import { __ } from '../../../utils/translationsUtility';
import { Box } from '@mui/material'; // Optional theme CSS
import Grid from '@mui/material/Grid';
import Button from '@mui/material/Button';
import CustomToolbar from './CustomToolbal';

const MuiTable = ({
  columnDefs,
  data,
}) => {
  const gridRef = useRef()

  // Example using Grid's API
  const buttonListener = useCallback(e => {
    gridRef.current.columnApi.resetColumnState()

  }, [])

  return (
    <Grid>
      {/*<div className="d-flex gap-1 mt-4 place-end">
                <Button variant="contained" onClick={() => buttonListener()}>
                    {__('Reset grid')}
                </Button>
            </div>*/}
      <Box sx={{
        height: 600,
        width: '100%',
        fontFamily: 'Source Sans Pro',
        '& .MuiDataGrid-toolbarContainer .MuiSelect-root': {
          fontSize: '13px',
          lineHeight: '13px',
          color: '#4075C3',
          fontFamily: '"Source Sans Pro", sans-serif',
          '& .MuiSvgIcon-root': { // Targeting the dropdown icon if necessary
            fill: '#4075C3', // This will change the color of the icon
          },
          // Apply additional styles if necessary, such as padding, margins, etc.
        },
        '& .material_table_header_style': {
          backgroundColor: '#f8f8f8',
        },
        '& .MuiDataGrid-columnHeaders': {
          fontWeight: 'bold',
        },
        '& .MuiDataGrid-columnHeaderTitle': {
          fontWeight: 'bold',
        },
        '& .MuiButton-textPrimary': {
          fontSize: '13px',
          lineHeight: '13px',
          color: '#4075C3',
          fontFamily: '"Source Sans Pro", sans-serif',
          textTransform: 'none', // Assuming you don't want uppercase text
          // Apply additional styles if necessary
        },
        '& .MuiDataGrid-filterForm': {
          '& .MuiInputBase-input': {
            fontSize: '13px', // Set the font size to match the tabs
            lineHeight: '13px', // Set the line height to match the tabs (if needed)
            fontFamily: '"Source Sans Pro", sans-serif', // Set the font family to match the tabs
            color: '#4075C3', // Set the font color to match the tabs
            // Apply additional styles if necessary
          },
        },
      }}>
        <DataGrid
          sx={{ fontFamily: 'Source Sans Pro' }}
          rows={data}
          columns={columnDefs}
          initialState={{
            pagination: {
              paginationModel: {
                page: 0,
                pageSize: 10,
              },
            },
          }}
          components={{
            Toolbar: CustomToolbar,
          }}
          // slots={{ toolbar: GridToolbar }}
          pageSizeOptions={[10, 20]}
          checkboxSelection
        />
      </Box>
    </Grid>
  )
}

export default MuiTable
