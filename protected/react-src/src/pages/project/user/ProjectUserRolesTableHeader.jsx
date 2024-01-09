import {__} from "../../../utils/translationsUtility";
import dayjs from "dayjs";
import {GridActionsCellItem} from "@mui/x-data-grid";
import {UserIcon} from "../../../components/common/icon/IconsSet";
import DeleteIcon from "@mui/icons-material/Delete";

const ProjectUserRolesTableHeader = ({deleteYesCallback}) => [
    {
        renderHeader: () => (
            <strong>{__('Name')}</strong>
        ),
        headerClassName: 'material_table_header_style',
        renderCell: (data) => {
            return data.row.userInfo?.name ?? '';
        },
        field: 'userInfo.name',
        flex: 1
    },
    {
        renderHeader: () => (
            <strong>{__('Email')}</strong>
        ),
        renderCell: (data) => {
            return data.row.userInfo?.email ?? '';
        },
        headerClassName: 'material_table_header_style',
        field: 'userInfo.email',
        flex: 1
    },
    {
        renderHeader: () => (
            <strong>{__('Role name')}</strong>
        ),
        renderCell: (data) => {
            return data.row.roleInfo?.name ?? '';
        },
        headerClassName: 'material_table_header_style',
        field: 'roleInfo.name',
        flex: 1
    },
    {
        renderHeader: () => (
            <strong>{__('Workspace')}</strong>
        ),
        renderCell: (data) => {
            if (data.row.target === 'project') {
                return '--'
            }
            const primary_language = data.row.projectInfo?.primary_language ?? 'en'
            const {title} = JSON.parse(data.row.workspaceInfo.i18n)
            return title[primary_language];
        },
        headerClassName: 'material_table_header_style',
        field: 'test2',
        flex: 1
    },
    {
        renderHeader: () => (
            <strong>{__('Added on')}</strong>
        ),
        headerClassName: 'material_table_header_style',
        field: 'created_date',
        type: 'date',
        flex: 1,
        valueFormatter: (params) => params.value && dayjs(params.value).format('YYYY-MM-DD HH:MM')
    },
    {
        renderHeader: () => (
            <strong>{__('Created By')}</strong>
        ),
        renderCell: (data) => {
            return data.row.createdByInfo?.name ?? '';
        },
        headerClassName: 'material_table_header_style',
        field: 'createdByInfo.name',
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
            <GridActionsCellItem icon={<DeleteIcon />} onClick={() => deleteYesCallback(params.id)} label={__('Delete User')} />,
        ],
    }
];

export default ProjectUserRolesTableHeader