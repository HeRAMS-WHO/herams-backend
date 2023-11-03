import { __ } from '../../utils/translationsUtility';
import Table from "../common/table/Table";
import {AddIcon} from "../common/icon/IconsSet";
import useRoleList from "../../hooks/useRole/useRoleList";
import RoleListHeader from "./RoleListHeader";
import {fetchDeleteRole} from "../../services/apiProxyService";

const RolesList = () => {
    const {rolesList, refreshRolesList} = useRoleList();
    const deleteYesCallback = (id) => {
        fetchDeleteRole(id)
            .then(() => {
                refreshRolesList();
            })
    }

    return (
        <>
            <div className="row mt-4 d-flex place-content-end">
                <div className="col-2 offset-10">
                    <button
                        className="btn btn-default"
                        onClick={() => { window.location.href='role/0/update' }}>
                        <AddIcon />
                        {__('Create new role')}
                    </button>
                </div>
            </div>
            <Table
                columnDefs={RoleListHeader({deleteYesCallback})}
                data={rolesList} />
        </>
    )

};

export default RolesList;
