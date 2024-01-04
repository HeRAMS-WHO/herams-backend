import {__} from "../../../utils/translationsUtility";
import {UserIcon} from "../../../components/common/icon/IconsSet";
import {GridActionsCellItem} from "@mui/x-data-grid";
import DeleteIcon from "@mui/icons-material/Delete";
import dayjs from "dayjs";

const UserIndexTableHeader = () => [
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
        headerClassName: 'material_table_header_style',
        field: 'name',
        flex: 1
    },
    {
        renderHeader: () => (
            <strong>{__('Email')}</strong>
        ),
        headerClassName: 'material_table_header_style',
        field: 'email',
        flex: 1
    },
    {
        renderHeader: () => (
            <strong>{__('Created on')}</strong>
        ),
        headerClassName: 'material_table_header_style',
        field: 'created_date',
        type: 'date',
        flex: 1,
        valueFormatter: (params) => params.value && dayjs(params.value).format('YYYY-MM-DD HH:MM')
    },
    {
        renderHeader: () => (
            <strong>{__('Last login')}</strong>
        ),
        headerClassName: 'material_table_header_style',
        field: 'last_login_date',
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
            <GridActionsCellItem icon={<UserIcon />} onClick={() => console.log('impersonate id ' + params.id)} label={__('Impersonate')} />,
            <GridActionsCellItem icon={<DeleteIcon />} onClick={() => console.log('delete id ' + params.id)} label={__('Delete User\'s Role')} />,
        ],
    }

];

export default UserIndexTableHeader
