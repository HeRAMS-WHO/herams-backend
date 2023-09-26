import React, {useEffect} from 'react';
import Table from "./Table";
import { fetchRoles} from "../services/apiProxyService";

import { __ } from './../utils/translationsUtility';

const CustomLinkRenderer = (params) => {
    const link = `/role/${params.data.id}/update`;
    return (
        <a href={link} className={"agGridAnkur"}>{params.data.name}</a>
    );
};
const columnDefs = [
    {
        headerName: __('Id'),
        field: 'id',
        checkboxSelection: false,
        filter: true,
        width: 80,
        pinned: 'left'
    },
    {
        headerName: __('Name'),
        checkboxSelection: false,
        filter: true,
        width: 300,
        pinned: 'left',
        cellRenderer: CustomLinkRenderer
    },
    {
        headerName: __('Scope'),
        field: 'scope',
        checkboxSelection: false,
        filter: true,
        width: 100
    },
    {
        headerName: __('Type'),
        field: 'type',
        checkboxSelection: false,
        filter: true,
        width: 100
    },
    {
        headerName: __('Project'),
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
        headerName: __('Created Date'),
        field: 'created_date',
        checkboxSelection: false,
        filter: true
    },
    {
        headerName: __('Created By'),
        checkboxSelection: false,
        filter: true,
        valueGetter: function(params) {
            return params.data.creatorUserInfo?.name;
        }
    },
    {
        headerName: __('Last Modified Date'),
        field: 'last_modified_date',
        checkboxSelection: false,
        filter: true
    },
    {
        headerName: __('Last Modified By'),
        field: 'last_modified_by',
        checkboxSelection: false,
        filter: true,
        valueGetter: function(params) {
            return params.data.updaterUserInfo?.name;
        }
    },
    {
        headerName: __('Actions'),
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