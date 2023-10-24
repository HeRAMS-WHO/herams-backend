import {
    fetchRoles, 
    fetchRolesInProject
} from "../../services/apiProxyService";
import {useEffect, useState} from "react";
function useRoleList(project_id = null){
    const [rolesList, setRolesList] = useState([]);
    const refreshRolesList = async () => {
        const rolesResponse = project_id === null ? 
            await fetchRoles() :
            await fetchRolesInProject(project_id);
        setRolesList(rolesResponse);
    }
    useEffect( () => {
        refreshRolesList();
    }, []);
    return {
        rolesList,
        refreshRolesList
    }
}
export default useRoleList;