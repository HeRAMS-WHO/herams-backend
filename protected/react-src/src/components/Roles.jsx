import React, {useEffect} from 'react';
import Table from "./Table";
import { fetchRoles} from "../services/apiProxyService";

const CustomLinkRenderer = (params) => {
    const link = `/roles/${params.data.id}`;
    return (
        <a href={link} className={"agGridAnkur"}>{params.data.name}</a>
    );
};
const columnDefs = [
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
        checkboxSelection: false,
        filter: true,
        width: 300,
        pinned: 'left',
        cellRenderer: CustomLinkRenderer
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
        headerName: 'Project',
        checkboxSelection: false,
        filter: true,
        cellRenderer: function(params) {
            if (params.data.projectInfo) {
                const { title } = JSON.parse(params.data.projectInfo.i18n);
                return title[params.data.projectInfo.primary_language];
            }
        },
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

];
const Roles = () => {
    return (
        <>
            <Table
                columnDefs={columnDefs}
                dataRetriever={fetchRoles} /> {/* This dataRetriever should be on ../services/apiProxyService.js */}
        </>
    )

};

export default Roles;