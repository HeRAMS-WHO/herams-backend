import {__} from "../../../utils/translationsUtility";
import DeleteButton from "../../../components/common/button/DeleteButton";
import { Link } from "react-router-dom";
const CustomLinkRenderer = (params) => {
    const link = `/admin/role/${params.data.id}/update`;
    return (
        <Link 
            to={link} 
            className={"agGridAnkur"}>
                {params.data.name}
        </Link>
    );
};

const comparatorProjectName = (a, b) => {
    let title1 = '', title2 = '';
    const lang1 = a?.primary_language ?? '';
    const lang2 = b?.primary_language ?? '';
    if (!!a) {
        const titlesA = JSON.parse(a.i18n);
        title1 = titlesA.title[lang1] ?? '';
    }
    if (!!b) {
        const titlesB = JSON.parse(b.i18n);
        title2 = titlesB.title[lang2] ?? '';
    }
    return title1.localeCompare(title2);
}


const RoleListHeader = ({deleteYesCallback}) => [
    {
        headerName: __('Id'),
        field: 'id',
        checkboxSelection: false,
        filter: true,
        width: 80,
        pinned: 'left'
    },
    {
        headerName: __('Name'),
        checkboxSelection: false,
        field: 'name',
        filter: true,
        width: 300,
        pinned: 'left',
        cellRenderer: CustomLinkRenderer,
        sortable: true,
        comparator: (a, b) => a.localeCompare(b)
    },
    {
        headerName: __('Scope'),
        field: 'scope',
        checkboxSelection: false,
        filter: true,
        width: 100,
        sortable: true,
        comparator: (a, b) => a.localeCompare(b)
    },
    {
        headerName: __('Type'),
        field: 'type',
        checkboxSelection: false,
        filter: true,
        width: 100,
        sortable: true,
        comparator: (a, b) => a.localeCompare(b),
    },
    {
        headerName: __('Project'),
        checkboxSelection: false,
        filter: true,
        field: 'projectInfo',
        cellRenderer: function (params) {
            if (params.data.projectInfo) {
                const {title} = JSON.parse(params.data.projectInfo.i18n);
                return title[params.data.projectInfo.primary_language];
            }
        },
        width: 120,
        sortable: true,
        comparator: (a, b) => comparatorProjectName(a, b),
    },
    {
        headerName: __('Created Date'),
        field: 'created_date',
        checkboxSelection: false,
        filter: true,
        sortable: true,
        comparator: (a, b) => a.localeCompare(b),
    },
    {
        headerName: __('Created By'),
        checkboxSelection: false,
        filter: true,
        valueGetter: function (params) {
            return params.data.creatorUserInfo?.name ?? '';
        },
        sortable: true,
        comparator: (a, b) => a.localeCompare(b),
    },
    {
        headerName: __('Last Modified Date'),
        field: 'last_modified_date',
        checkboxSelection: false,
        filter: true,
        sortable: true,
        comparator: (a, b) => a.localeCompare(b),
    },
    {
        headerName: __('Last Modified By'),
        field: 'updaterUserInfo',
        checkboxSelection: false,
        filter: true,
        valueGetter: function (params) {
            return params.data.updaterUserInfo?.name ?? '';
        },
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
            const confirmationText = __('Are you sure you want to delete the role {}?').replace('{}', params.data.name);
            return <DeleteButton
                title={__('Delete Role')}
                html={confirmationText}
                confirmButtonText={__('Delete')}
                cancelButtonText={__('Cancel')}
                yesCallback={() => deleteYesCallback(params.data.id)}/>
        },

    }

];

export default RoleListHeader;