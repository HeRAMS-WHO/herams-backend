import {__} from "../../utils/translationsUtility";
import DeleteButton from "../../components/common/button/DeleteButton";

const WorkspaceUserRolesTableHeader = ({deleteYesCallback}) => [
    {
        headerName: __('Name'),
        checkboxSelection: false,
        field: 'userInfo.name',
        filter: true,
        width: 200,
        pinned: 'left',
        sortable: true,
        comparator: (a, b) => a.localeCompare(b)
    },
    {
        headerName: __('Email'),
        field: 'userInfo.email',
        checkboxSelection: false,
        filter: true,
        width: 200,
        sortable: true,
        comparator: (a, b) => a.localeCompare(b)
    },
    {
        headerName: __('Role name'),
        field: 'roleInfo.name',
        checkboxSelection: false,
        filter: true,
        width: 200,
        sortable: true,
        comparator: (a, b) => a.localeCompare(b),
    },
    {
        headerName: __('Added on'),
        field: 'created_date',
        checkboxSelection: false,
        filter: true,
        sortable: true,
        comparator: (a, b) => a.localeCompare(b),
    },
    {
        headerName: __('Added by'),
        checkboxSelection: false,
        filter: true,
        valueGetter: ({data}) => data.createdByInfo?.name ?? '',
        sortable: true,
        comparator: (a, b) => a.localeCompare(b),
    },
    {
        headerName: __('Actions'),
        field: 'actions',
        checkboxSelection: false,
        filter: true,
        pinned: 'right',
        cellRenderer: function (params) {
            const confirmationText = __("Are you sure you want to delete the user's role?");
            return <DeleteButton
                title={__("Delete User's Role")}
                html={confirmationText}
                confirmButtonText={__('Delete')}
                cancelButtonText={__('Cancel')}
                yesCallback={() => deleteYesCallback(params.data.id)}/>
        },

    }

];

export default WorkspaceUserRolesTableHeader;