import {__} from "../../../utils/translationsUtility";
import Link from "@mui/material/Link";
import replaceVariablesAsText from "../../../utils/replaceVariablesAsText";

const ProjectIndexTableHeader = () => [
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
            <strong>{__('Project Name')}</strong>
        ),
        renderCell: (params) => (
            <Link href={replaceVariablesAsText( `/admin/project/${params.id}/workspace`)}>{params.value}</Link>
        ),
        headerClassName: 'material_table_header_style',
        field: 'name',
        flex: 1
    },
    {
        headerClassName: 'material_table_header_style',
        renderHeader: () => (
            <strong>{__('Workspaces')}</strong>
        ),
        field: 'workspaceCount',
        type: 'number',
        flex: 1
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
            <strong>{__('Project coordinator')}</strong>
        ),
        headerClassName: 'material_table_header_style',
        field: 'coordinatorName',
        flex: 1
    }
];

export default ProjectIndexTableHeader
