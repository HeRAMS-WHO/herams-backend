import {__} from "../../../utils/translationsUtility";
import {UserIcon} from "../../../components/common/icon/IconsSet";
import {GridActionsCellItem} from "@mui/x-data-grid";
import DeleteIcon from "@mui/icons-material/Delete";
import dayjs from "dayjs";

const CustomLinkRenderer = ({data}) => {
    const link = `/admin/user/${data.id}`;
    return (
        <Link to={link} className={"agGridAnkur"}>{data.name}</Link>
    );
};

const UserIndexTableHeader = () => [
    {
        headerName: __('Id'),
        field: 'id',
        type: 'number',
        width: 50
    },
    {
        headerName: __('Name'),
        field: 'name',
        width: 200
    },
    {
        headerName: __('Email'),
        field: 'email',
        width: 200
    },
    {
        headerName: __('Created on'),
        field: 'created_date',
        type: 'date',
        width: 150,
        valueFormatter: (params) => params.value && dayjs(params.value).format('YYYY-MM-DD HH:MM')
    },
    {
        headerName: __('Last login'),
        field: 'last_login_date',
        type: 'date',
        width: 150,
        valueFormatter: (params) => params.value && dayjs(params.value).format('YYYY-MM-DD HH:MM')
    },
    {
        headerName: __('Actions'),
        field: 'actions',
        type: 'actions',
        getActions: (params) => [
            <GridActionsCellItem icon={<UserIcon />} onClick={() => console.log('impersonate id ' + params.id)} label={__('Impersonate')} />,
            <GridActionsCellItem icon={<DeleteIcon />} onClick={() => console.log('delete id ' + params.id)} label={__('Delete User\'s Role')} />,
        ],
    }

];

export default UserIndexTableHeader
