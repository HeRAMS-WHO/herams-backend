import
    React,
    {
        useState,
        useRef,
        useEffect,
        useCallback,
        useMemo
    } from 'react';
import { AgGridReact } from 'ag-grid-react';

import 'ag-grid-community/styles/ag-grid.css'; // Core grid CSS, always needed
import 'ag-grid-community/styles/ag-theme-alpine.css';
import {__} from "../../../utils/translationsUtility"; // Optional theme CSS

const Table = ({columnDefs, data}) => {

    const gridRef = useRef();
    // DefaultColDef sets props common to all Columns
    const defaultColDef = useMemo( ()=> ({
        sortable: true
    }));

    // Example of consuming Grid Event
    const cellClickedListener = useCallback( event => {
        console.log('cellClicked', event);
    }, []);


    // Example using Grid's API
    const buttonListener = useCallback( e => {
        gridRef.current.columnApi.resetColumnState();

    }, []);

    return (
        <div>
            <div className="row mt-2 d-flex text-right place-content-end">
                <div className="col-2 offset-10 display-flex ">
                    <button
                        className="btn btn-secondary "
                        onClick={buttonListener}>
                        {__('Reset grid')}
                    </button>
                </div>
            </div>
            {/* On div wrapping Grid a) specify theme CSS Class Class and b) sets Grid size */}
            <div className="ag-theme-alpine" style={{width: '100%', height: 500}}>
                <AgGridReact
                    ref={gridRef} // Ref for accessing Grid's API
                    rowData={data} // Row Data for Rows
                    columnDefs={columnDefs} // Column Defs for Columns
                    defaultColDef={defaultColDef} // Default Column Properties
                    animateRows={true} // Optional - set to 'true' to have rows animate when sorted
                    rowSelection='multiple' // Options - allows click selection of rows
                    onCellClicked={cellClickedListener} // Optional - registering for Grid Event
                />
            </div>
        </div>
    );
}

export default Table;