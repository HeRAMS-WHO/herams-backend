import {fetchRoles} from "../../services/apiProxyService";
import {useEffect, useState} from "react";
function useRoleList(){
    const [rolesList, setRolesList] = useState([]);
    const refreshRolesList = async () => {
        const rolesResponse = await fetchRoles()
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