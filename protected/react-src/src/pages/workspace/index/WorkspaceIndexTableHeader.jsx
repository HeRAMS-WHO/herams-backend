import {__} from "../../../utils/translationsUtility";
import Link from "@mui/material/Link";
import dayjs from "dayjs";

const WorkspacesIndexTableHeader = () => [
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
            <strong>{__('Title')}</strong>
        ),
        renderCell: (params) => (
            <Link href={`/admin/project/${params.row.project_id}/workspace/${params.id}/HSDU`}>{params.value}</Link>
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
        headerClassName: 'material_table_header_style',
        renderHeader: () => (
            <strong>{__('Contributors')}</strong>
        ),
        field: 'contributorCount',
        type: 'number',
        flex: 1
    },
    {
        headerClassName: 'material_table_header_style',
        renderHeader: () => (
            <strong>{__('HSDUs')}</strong>
        ),
        field: 'facilityCount',
        type: 'number',
        flex: 1
    },
    {
        headerClassName: 'material_table_header_style',
        renderHeader: () => (
            <strong>{__('Responses')}</strong>
        ),
        field: 'responseCount',
        type: 'number',
        flex: 1
    },
    {
        renderHeader: () => (
            <strong>{__('Workspace owner')}</strong>
        ),
        headerClassName: 'material_table_header_style',
        field: 'created_by',
        flex: 1
    }
];

export default WorkspacesIndexTableHeader
