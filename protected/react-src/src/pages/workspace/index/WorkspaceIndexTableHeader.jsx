import {__} from "../../../utils/translationsUtility";

const CustomLinkRenderer = ({data}) => {
    const { projectId } = params.value;
    const link = `/admin/project/${projectId}/workspace/${data.id}/HSDU`;
    return (
        <Link to={link} className={"agGridAnkur"}>{data.name}</Link>
    );
};

const WorkspacesIndexTableHeader = () => [
    {
        headerName: __('Id'),
        field: 'id',
        checkboxSelection: false,
        filter: true,
        width: 100,
        sortable: true,
    },
    {
        headerName: __('Title'),
        checkboxSelection: false,
        field: 'name',
        filter: true,
        width: 200,
        sortable: true,
        valueGetter: ({data}) => data.name,
        cellRenderer: CustomLinkRenderer,
        comparator: (a, b) => a.localeCompare(b)
    },
    {
        headerName: __('Date of update'),
        field: 'date_of_update',
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
        headerName: __('# Workspace owner'),
        field: 'created_by',
        checkboxSelection: false,
        filter: true,
        width: 200,
        sortable: true,
    },
];

export default WorkspacesIndexTableHeader
