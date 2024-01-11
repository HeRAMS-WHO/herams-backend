import {__} from "../../../utils/translationsUtility";
import Link from "@mui/material/Link";
import dayjs from "dayjs";
import {GridActionsCellItem} from "@mui/x-data-grid";
import DeleteIcon from "@mui/icons-material/Delete";

const RoleListHeader = ({deleteRole}) => [
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
            <strong>{__('Name')}</strong>
        ),
        renderCell: (params) => (
            <Link href={`/admin/role/${params.id}/update`}>{params.value}</Link>
        ),
        headerClassName: 'material_table_header_style',
        field: 'name',
        flex: 1
    },
    {
        renderHeader: () => (
            <strong>{__('Scope')}</strong>
        ),
        headerClassName: 'material_table_header_style',
        field: 'scope',
        flex: 1
    },
    {
        renderHeader: () => (
            <strong>{__('Type')}</strong>
        ),
        headerClassName: 'material_table_header_style',
        field: 'type',
        flex: 1
    },
    {
        renderHeader: () => (
            <strong>{__('Project')}</strong>
        ),
        headerClassName: 'material_table_header_style',
        field: 'projectInfo',
        renderCell: (data) => {
            if (data.field === 'projectInfo' && data.value) {
                const {title} = JSON.parse(data.value.i18n);
                return title[data.value.primary_language];
            }
        },
        flex: 1
    },
    {
        renderHeader: () => (
            <strong>{__('Created date')}</strong>
        ),
        headerClassName: 'material_table_header_style',
        field: 'created_date',
        type: 'date',
        flex: 1,
        valueFormatter: (params) => params.value && dayjs(params.value).format('YYYY-MM-DD HH:MM')
    },
    {
        renderHeader: () => (
            <strong>{__('Actions')}</strong>
        ),
        headerClassName: 'material_table_header_style',
        field: 'actions',
        type: 'actions',
        flex: 1,
        getActions: (params) => [
            <GridActionsCellItem icon={<DeleteIcon />} onClick={() => deleteRole(params.id)} label={__('Delete User\'s Role')} />,
        ],
    }

];

export default RoleListHeader;