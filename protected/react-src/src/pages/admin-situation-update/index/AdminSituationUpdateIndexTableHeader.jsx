import {__} from "../../../utils/translationsUtility";
import dayjs from "dayjs";
import {GridActionsCellItem} from "@mui/x-data-grid";
import EditIcon from '@mui/icons-material/Edit';
import VisibilityIcon from '@mui/icons-material/Visibility';
import DeleteIcon from "@mui/icons-material/Delete";
import replaceVariablesAsText from "../../../utils/replaceVariablesAsText";

const SituationUpdateIndexTableHeader = () => {
    return [
        {
            headerClassName: 'material_table_header_style',
            renderHeader: () => (
                <strong>{__('Id')}</strong>
            ),
            field: 'id',
            type: 'number',
            width: 30
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
                <strong>{__('HSDU Name')}</strong>
            ),
            headerClassName: 'material_table_header_style',
            field: 'name',
            flex: 1
        },
        {
            renderHeader: () => (
                <strong>{__('Tier')}</strong>
            ),
            headerClassName: 'material_table_header_style',
            field: 'HSDU_TYPE_tier',
            flex: 1
        },
        {
            renderHeader: () => (
                <strong>{__('HSDU Type')}</strong>
            ),
            headerClassName: 'material_table_header_style',
            field: 'HSDU_TYPE',
            flex: 1
        },
        {
            renderHeader: () => (
                <strong>{__('Status')}</strong>
            ),
            headerClassName: 'material_table_header_style',
            field: 'status',
            flex: 1
        },
        {
            renderHeader: () => (
                <strong>{__('Last modified on')}</strong>
            ),
            headerClassName: 'material_table_header_style',
            field: 'last_modified_date',
            type: 'date',
            flex: 1,
            valueFormatter: (params) => params.value && dayjs(params.value).format('YYYY-MM-DD HH:MM')
        },
        {
            renderHeader: () => (
                <strong>{__('Last modified by')}</strong>
            ),
            headerClassName: 'material_table_header_style',
            field: 'last_modified_by',
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
            getActions: (params) => [
                <GridActionsCellItem icon={<EditIcon />} onClick={() => {return window.location.href = replaceVariablesAsText(`/admin/project/:projectId/workspace/:workspaceId/HSDU/:hsduId/admin-update/${params.id}/edit`)}} label={__('Edit')} />,
                <GridActionsCellItem icon={<VisibilityIcon />} onClick={() => {return window.location.href = replaceVariablesAsText(`/admin/project/:projectId/workspace/:workspaceId/HSDU/:hsduId/admin-update/${params.id}`)}} label={__('View')} />,
                <GridActionsCellItem icon={<DeleteIcon />} onClick={() => {return window.location.href = replaceVariablesAsText(`/admin/project/:projectId/workspace/:workspaceId/HSDU/:hsduId/settings`)}} label={__('Delete')} />,
            ],
        }
    ];
}

export default SituationUpdateIndexTableHeader
