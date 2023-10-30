import {fetchRoles, fetchRolesInProject, fetchRolesInWorkspace} from "../../services/apiProxyService";
import {useEffect, useState} from "react";

function useRoleList({projectId = null, workspaceId = null}) {
    const [rolesList, setRolesList] = useState([]);
    const refreshRolesList = async () => {
        let rolesResponse;
        if (projectId !== null) {
            rolesResponse = await fetchRolesInProject(projectId);
        }
        if (workspaceId !== null) {
            rolesResponse = await fetchRolesInWorkspace(workspaceId);
        }
        if (projectId === null && workspaceId === null) {
            rolesResponse = await fetchRoles();
        }
        setRolesList(rolesResponse);
    }
    useEffect(() => {
        refreshRolesList();
    }, []);
    return {
        rolesList,
        refreshRolesList
    }
}

export default useRoleList;