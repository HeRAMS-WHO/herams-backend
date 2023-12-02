import {__} from "../../../utils/translationsUtility";
import DeleteButton from "../../../components/common/button/DeleteButton";
import {UserIcon} from "../../../components/common/icon/IconsSet";
import { Link } from "react-router-dom";
const CustomLinkRenderer = (params) => {
    const link = `/admin/user/${params.data.id}`;
    return (
        <Link to={link} className={"agGridAnkur"}>{params.data.name}</Link>
    );
};

const UserIndexTableHeader = () => [
    {
        headerName: __('Id'),
        field: 'id',
        checkboxSelection: false,
        filter: true,
        width: 100,
        sortable: true,
    },
    {
        headerName: __('Name'),
        checkboxSelection: false,
        field: 'name',
        filter: true,
        width: 200,
        sortable: true,
        valueGetter: (params) => params.data.name,
        cellRenderer: CustomLinkRenderer,
        comparator: (a, b) => a.localeCompare(b)
    },
    {
        headerName: __('Email'),
        field: 'email',
        checkboxSelection: false,
        filter: true,
        width: 200,
        sortable: true,
        comparator: (a, b) => a.localeCompare(b)
    },
    {
        headerName: __('Created on'),
        field: 'created_date',
        checkboxSelection: false,
        filter: true,
        width: 200,
        sortable: true,
        comparator: (a, b) => a.localeCompare(b),
    },
    {
        headerName: __('Last login'),
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
        cellRenderer: function (params) {
            const confirmationText = __("Are you sure you want to delete the user's role?");
            return (
                <>
                    <div className='d-flex'>
                        <div>
                            <button className="bg-transparent borderless cursor-pointer">
                                <UserIcon title={__('Impersonate')}/>
                            </button>
                        </div>
                        <div>
                            <DeleteButton
                                title={__("Delete User's Role")}
                                html={confirmationText}
                                confirmButtonText={__('Delete')}
                                cancelButtonText={__('Cancel')}
                            />
                        </div>
                    </div>
                </>
            )
        },

    }

];

export default UserIndexTableHeader