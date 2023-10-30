import Table from "../common/table/Table";
import useRoleList from "../../hooks/Role/useRoleList";
import RoleListHeader from "./RoleListHeader";
import {fetchDeleteRole} from "../../services/apiProxyService";
import ActionOnHeaderLists from "../common/ActionOnHeaderLists";

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
            <ActionOnHeaderLists
                label={'Create new role'}
                url={'role/0/update'}/>
            <Table
                columnDefs={RoleListHeader({deleteYesCallback})}
                data={rolesList}/>
        </>
    )

};

export default RolesList;