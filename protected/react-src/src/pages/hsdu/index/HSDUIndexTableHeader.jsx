import {__} from "../../../utils/translationsUtility";
import TableButtonWithLInk from "../../../components/common/button/TableButtonWithLink";

const CustomLinkRenderer = ({data}) => {
    const {projectId, workspaceId} = params.value;
    const link = `/admin/project/${projectId}/workspace/${workspaceId}/HSDU/${data.id}/situation-update`;
    return (
        <Link to={link} className={"agGridAnkur"}>{data.name}</Link>
    );
};

///admin/project/:projectId/workspace/:workspaceId/HSDU/:hsduId/situation-update/:updateID/edit

const HSDUIndexTableHeader = () => {
    const { projectId, workspaceId } = params.value;

    return [
        {
            headerName: __('Id'),
            field: 'id',
            checkboxSelection: false,
            filter: true,
            width: 100,
            sortable: true,
        },
        {
            headerName: __('HSDU name'),
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
            headerName: __('HSDU Code'),
            field: 'HSDU_CODE',
            checkboxSelection: false,
            filter: true,
            width: 200,
            sortable: true,
            comparator: (a, b) => a.localeCompare(b),
        },
        {
            headerName: __('HSDU Type'),
            field: 'HSDU_TYPE_tier',
            checkboxSelection: false,
            filter: true,
            width: 200,
            sortable: true,
        },
        {
            headerName: __('Functionality'),
            field: 'P2_FUNCIONALITY',
            checkboxSelection: false,
            filter: true,
            width: 200,
            sortable: true,
        },
        {
            headerName: __('Accesibility'),
            field: 'P2_ACCESSIBILITY',
            checkboxSelection: false,
            filter: true,
            width: 200,
            sortable: true,
        },
        {
            headerName: __('Actions'),
            field: 'actions',
            checkboxSelection: false,
            filter: true,
            pinned: 'right',
            cellRenderer: function ({data}) {
                // Construct the URL using projectId, workspaceId, and data.id
                const url = `/admin/project/${projectId}/workspace/${workspaceId}/HSDU/${data.id}/situation-update/`;

                return (
                    <TableButtonWithLInk
                        buttons={[
                            {
                                label: __('Update Situation'),
                                class: "btn btn-default",
                                icon: "add_box",
                                url: url // Use the constructed URL
                            }
                        ]}
                    />
                );
            },

        }

    ];
}
export default HSDUIndexTableHeader
