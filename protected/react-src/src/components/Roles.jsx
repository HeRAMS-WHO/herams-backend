import React, { useState, useRef, useEffect, useMemo, useCallback} from 'react';
import { AgGridReact } from 'ag-grid-react'; // the AG Grid React Component
import * as apiService from './../services/apiService';
import {getRoles} from "./../services/apiService";
import 'ag-grid-community/styles/ag-grid.css'; // Core grid CSS, always needed
import 'ag-grid-community/styles/ag-theme-alpine.css'; // Optional theme CSS

const Roles = () => {

    const gridRef = useRef(); // Optional - for accessing Grid's API
    const [rowData, setRowData] = useState(); // Set rowData to Array of Objects, one Object per Row


    const [columnDefs, setColumnDefs] = useState([
        {
            headerName: 'Id',
            field: 'id',
            checkboxSelection: false,
            filter: true,
            width: 80,
            pinned: 'left'
        },
        {
            headerName: 'Name',
            field: 'name',
            checkboxSelection: false,
            filter: true,
            width: 300,
            pinned: 'left'
        },
        {
            headerName: 'Scope',
            field: 'scope',
            checkboxSelection: false,
            filter: true,
            width: 100
        },
        {
            headerName: 'Type',
            field: 'type',
            checkboxSelection: false,
            filter: true,
            width: 100
        },
        {
            headerName: 'Project ID',
            field: 'project_id',
            checkboxSelection: false,
            filter: true,
            width: 120
        },
        {
            headerName: 'Created Date',
            field: 'created_date',
            checkboxSelection: false,
            filter: true
        },
        {
            headerName: 'Created By',
            checkboxSelection: false,
            filter: true,
            valueGetter: function(params) {
                return params.data.creatorUserInfo?.name;
            }
        },
        {
            headerName: 'Last Modified Date',
            field: 'last_modified_date',
            checkboxSelection: false,
            filter: true
        },
        {
            headerName: 'Last Modified By',
            field: 'last_modified_by',
            checkboxSelection: false,
            filter: true,
            valueGetter: function(params) {
                return params.data.updaterUserInfo?.name;
            }
        },
        {
            headerName: 'Actions',
            field: 'actions',
            checkboxSelection: false,
            filter: true,
            pinned: 'right'
        }

    ]);

    // DefaultColDef sets props common to all Columns
    const defaultColDef = useMemo( ()=> ({
        sortable: true
    }));

    // Example of consuming Grid Event
    const cellClickedListener = useCallback( event => {
        console.log('cellClicked', event);
    }, []);

    // Example load data from server
    useEffect(() => {
        getRoles().then(data => setRowData(data));
    }, []);

    // Example using Grid's API
    const buttonListener = useCallback( e => {
        gridRef.current.api.deselectAll();
    }, []);

    return (
        <div>

            {/* Example using Grid's API */}
            <button onClick={buttonListener}>Push Me</button>

            {/* On div wrapping Grid a) specify theme CSS Class Class and b) sets Grid size */}
            <div className="ag-theme-alpine" style={{width: '100%', height: 500}}>

                <AgGridReact
                    ref={gridRef} // Ref for accessing Grid's API

                    rowData={rowData} // Row Data for Rows

                    columnDefs={columnDefs} // Column Defs for Columns
                    defaultColDef={defaultColDef} // Default Column Properties

                    animateRows={true} // Optional - set to 'true' to have rows animate when sorted
                    rowSelection='multiple' // Options - allows click selection of rows

                    onCellClicked={cellClickedListener} // Optional - registering for Grid Event
                />
            </div>
        </div>
    );
};

export default Roles;