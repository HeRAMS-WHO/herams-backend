import {__} from "../../../utils/translationsUtility";
import replaceVariablesAsText from "../../../utils/replaceVariablesAsText";

const CustomLinkRenderer = ({data}) => {
    const link = `/admin/user/${data.id}`;
    return (
        <Link to={replaceVariablesAsText( `/admin/project/${data.id}/workspace`)} className={"agGridAnkur"}>{data.i18n['title'][data.primary_language]}</Link>
    );
};

const ProjectIndexTableHeader = () => [
    {
        headerName: __('Id'),
        field: 'id',
        checkboxSelection: false,
        filter: true,
        width: 100,
        sortable: true,
    },
    {
        headerName: __('Project Name'),
        checkboxSelection: false,
        field: 'name',
        filter: true,
        width: 200,
        sortable: true,
        valueGetter: ({data}) => data.i18n['title'][data.primary_language],
        cellRenderer: CustomLinkRenderer,
        comparator: (a, b) => a.localeCompare(b)
    },
    {
        headerName: __('# Workspaces'),
        field: 'workspaceCount',
        checkboxSelection: false,
        filter: true,
        width: 200,
        sortable: true,
        comparator: (a, b) => a.localeCompare(b)
    },
    {
        headerName: __('# Contributors'),
        field: 'contributorCount',
        checkboxSelection: false,
        filter: true,
        width: 200,
        sortable: true,
        comparator: (a, b) => a.localeCompare(b),
    },
    {
        headerName: __('# HSDUs'),
        field: 'facilityCount',
        checkboxSelection: false,
        filter: true,
        width: 200,
        sortable: true,
    },
    {
        headerName: __('# Responses'),
        field: 'responseCount',
        checkboxSelection: false,
        filter: true,
        width: 200,
        sortable: true,
    },
    {
        headerName: __('Project coordinator'),
        field: 'coordinatorName',
        checkboxSelection: false,
        filter: true,
        width: 200,
        sortable: true,
    },
];

export default ProjectIndexTableHeader
