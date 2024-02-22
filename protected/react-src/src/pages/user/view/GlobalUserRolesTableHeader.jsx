import {__} from "../../../utils/translationsUtility";
import DeleteButton from "../../../components/common/button/DeleteButton";

const workspacesNameComparator = (a, b) => {
    return a.localeCompare(b)
}
const GlobalUserRolesTableHeader = ({deleteYesCallback}) => [
    {
        headerName: __('Role name'),
        checkboxSelection: false,
        field: 'role_info.name',
        filter: true,
        width: 200,
        pinned: 'left',
        sortable: true,
        comparator: (a, b) => a.localeCompare(b)
    },
    {
        headerName: __('Project'),
        checkboxSelection: false,
        filter: true,
        valueGetter: ({data}) => {
            if (data.projectInfo === null || data.projectInfo === undefined || data.projectInfo === '' || data.projectInfo === '--') {
                return '--'
            }
            const primary_language = data.projectInfo?.primary_language ?? 'en'
            const {title} = (data.projectInfo.i18n)
            return title[primary_language];
        },
        width: 200,
        sortable: true,
        comparator: (a, b) => workspacesNameComparator(a, b)
    },
    {
        headerName: __('Workspace'),
        checkboxSelection: false,
        filter: true,
        valueGetter: ({data}) => {
            if (data.workspaceInfo === null || data.workspaceInfo === undefined || data.workspaceInfo === '' || data.workspaceInfo === '--') {
                return '--'
            }
            const primary_language = data.projectInfo?.primary_language ?? 'en'
            const {title} = (data.workspaceInfo.i18n)
            return title[primary_language];
        },
        width: 200,
        sortable: true,
        comparator: (a, b) => workspacesNameComparator(a, b)
    },
    {
        headerName: __('Added on'),
        field: 'created_at',
        checkboxSelection: false,
        filter: true,
        sortable: true,
        comparator: (a, b) => a.localeCompare(b),
    },
    {
        headerName: __('Created By'),
        checkboxSelection: false,
        filter: true,
        valueGetter: ({data}) => data.created_by_info?.name ?? '',
        sortable: true,
        comparator: (a, b) => a.localeCompare(b),
    },
    {
        headerName: __('Actions'),
        field: 'actions',
        checkboxSelection: false,
        filter: true,
        pinned: 'right',
        cellRenderer: function ({data}) {
            if (data.target.toLowerCase() !== 'global') {
                return null
            }
            const confirmationText = __("Are you sure you want to delete the user's role?");
            return <DeleteButton
                title={__("Delete User's Role")}
                html={confirmationText}
                confirmButtonText={__('Delete')}
                cancelButtonText={__('Cancel')}
                yesCallback={() => deleteYesCallback(data.id)}/>
        },

    }

];

export default GlobalUserRolesTableHeader