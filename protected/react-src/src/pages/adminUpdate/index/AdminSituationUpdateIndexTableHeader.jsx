import {__} from "../../../utils/translationsUtility";
import DeleteButton from "../../../components/common/button/DeleteButton";
import {UserIcon} from "../../../components/common/icon/IconsSet";
import TableIconWithLink from "../../../components/common/icon/TableIconWithLink";

// const CustomLinkRenderer = ({data}) => {
//     const link = `/admin/user/${data.id}`;
//     return (
//         <Link to={link} className={"agGridAnkur"}>{data.name}</Link>
//     );
// };

const SituationUpdateIndexTableHeader = () => {
    return [
        {
            headerName: __('Id'),
            field: 'id',
            checkboxSelection: false,
            filter: true,
            width: 50,
            sortable: true,
        },
        {
            headerName: __('Date of update'),
            checkboxSelection: false,
            field: 'date_of_update',
            filter: true,
            width: 150,
            sortable: true,
            comparator: (a, b) => a.localeCompare(b)
        },
        {
            headerName: __('Building condition'),
            field: 'P2_COND_BUILDING',
            checkboxSelection: false,
            filter: true,
            width: 200,
            sortable: true,
            comparator: (a, b) => a.localeCompare(b)
        },
        {
            headerName: __('Functionality'),
            field: 'P2_FUNCIONALITY',
            checkboxSelection: false,
            filter: true,
            width: 200,
            sortable: true,
            comparator: (a, b) => a.localeCompare(b),
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
            headerName: __('Status'),
            field: 'status',
            checkboxSelection: false,
            filter: true,
            width: 200,
            sortable: true,
        },
        {
            headerName: __('Last modified on'),
            field: 'last_modified_date',
            checkboxSelection: false,
            filter: true,
            width: 200,
            sortable: true,
        },
        {
            headerName: __('Last modified by'),
            field: 'last_modified_by',
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
            cellRenderer: function ({data}) {
                return (
                    <TableIconWithLink
                        icons={[
                            {
                                url: `/facility/${data.facilityId}/edit-situation/${data.id}`,
                                iconName: 'edit',
                                class: '' // Add any additional classes if needed
                            },
                            {
                                url: `/facility/${data.facilityId}/view-situation/${data.id}`,
                                iconName: 'visibility',
                                class: '' // Add any additional classes if needed
                            },
                            {
                                url: `/facility/${data.facilityId}/delete-situation/${data.id}`,
                                iconName: 'delete',
                                class: '' // Add any additional classes if needed
                            }
                        ]}
                    />
                );
            },
        }
    ];
}

export default SituationUpdateIndexTableHeader
