import {__} from "../../../utils/translationsUtility";
import Typography from '@mui/material/Typography'
import replaceVariablesAsText from "../../../utils/replaceVariablesAsText";
import Link from "@mui/material/Link";
import dayjs from "dayjs";
import Button from "@mui/material/Button";
import EditIcon from '@mui/icons-material/Edit';
import React from "react";

const HSDUIndexTableHeader = () => {
    return [
        {
            headerClassName: 'material_table_header_style',
            renderHeader: () => (
                <strong>{__('Id')}</strong>
            ),
            field: 'id',
            type: 'number',
            width: 80
        },
        {
            renderHeader: () => (
                <strong>{__('HSDU name')}</strong>
            ),
            renderCell: (params) => (
                <Link href={replaceVariablesAsText(`/admin/project/:projectId/workspace/:workspaceId/HSDU/`+params.id+'/situation-update')}>{params.value}</Link>
            ),
            headerClassName: 'material_table_header_style',
            field: 'name',
            flex: 1
        },
        {
            renderHeader: () => (
                <strong>{__('Date of update')}</strong>
            ),
            headerClassName: 'material_table_header_style',
            field: 'date_of_update',
            type: 'date',
            flex: 1,
            valueFormatter: (params) => params.value && dayjs(params.value).format('YYYY-MM-DD HH:MM')
        },
        {
            renderHeader: () => (
                <strong>{__('HSDU Code')}</strong>
            ),
            headerClassName: 'material_table_header_style',
            field: 'HSDU_CODE',
            flex: 1
        },
        {
            renderHeader: () => (
                <strong>{__('HSDU Type')}</strong>
            ),
            headerClassName: 'material_table_header_style',
            field: 'HSDU_TYPE_tier',
            flex: 1
        },
        {
            renderHeader: () => (
                <strong>{__('Functionality')}</strong>
            ),
            headerClassName: 'material_table_header_style',
            field: 'P2_FUNCIONALITY',
            flex: 1
        },
        {
            renderHeader: () => (
                <strong>{__('Accesibility')}</strong>
            ),
            headerClassName: 'material_table_header_style',
            field: 'P2_ACCESSIBILITY',
            flex: 1
        },
        {
            renderHeader: () => (
                <strong>{__('Actions')}</strong>
            ),
            headerClassName: 'material_table_header_style',
            field: 'actions',
            type: 'actions',
            flex: 1,
            renderCell: (params) => {
                if (params.can_receive_situation_update === 0) {
                    return (
                        <Typography variant="caption" display="block" gutterBottom>
                            {__("No updates expected")}
                        </Typography>
                    );
                } else {
                    return (
                        <Button startIcon={<EditIcon />} href={replaceVariablesAsText(`/admin/project/:projectId/workspace/:workspaceId/HSDU/${params.id}/situation-update/create`)}
                                size="small" permissions={['HSDU_CREATE']}>
                            {__('Update')}
                        </Button>
                    );
                }
            },
        }
    ];
}

export default HSDUIndexTableHeader
