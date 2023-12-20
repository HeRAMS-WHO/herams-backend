import React, {useCallback, useMemo, useRef} from 'react';
import { DataGrid, GridToolbar } from '@mui/x-data-grid';

import {__} from "../../../utils/translationsUtility";
import {Box} from "@mui/material"; // Optional theme CSS
import Grid from "@mui/material/Grid";
import Button from "@mui/material/Button";

const MuiTable = ({columnDefs, data}) => {
    const gridRef = useRef();


    // Example using Grid's API
    const buttonListener = useCallback(e => {
        gridRef.current.columnApi.resetColumnState();

    }, []);

    return (
        <Grid>
            {/*<div className="d-flex gap-1 mt-4 place-end">
                <Button variant="contained" onClick={() => buttonListener()}>
                    {__('Reset grid')}
                </Button>
            </div>*/}
            <Box sx={{height: 600, width: '100%', fontFamily: 'Source Sans Pro',
                '& .material_table_header_style': {
                    backgroundColor: '#f8f8f8'
                },}}>
                <DataGrid
                    sx={{fontFamily: 'Source Sans Pro'}}
                    rows={data}
                    columns={columnDefs}
                    initialState={{
                        pagination: {
                            paginationModel: { page: 0, pageSize: 10 },
                        },
                    }}
                    slots={{ toolbar: GridToolbar }}
                    pageSizeOptions={[10, 20]}
                    checkboxSelection
                />
            </Box>
        </Grid>
    );
}

export default MuiTable;