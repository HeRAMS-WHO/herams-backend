import Table from "../../components/common/table/Table";
import useRoleList from "../../hooks/Role/useRoleList";
import RoleListHeader from "./index/RoleListHeader";
import {fetchDeleteRole} from "../../services/apiProxyService";
import ActionOnHeaderLists from "../../components/common/ActionOnHeaderLists";

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
                permissions={['ROLE_CREATE']}
                url={'/admin/role/create'}/>
            <Table
                columnDefs={RoleListHeader({deleteYesCallback})}
                data={rolesList}/>
        </>
    )

};

export default RolesList;
