import {fetchRoles, fetchRolesInProject, fetchRolesInWorkspace} from "../../services/apiProxyService";
import {useEffect, useState} from "react";

function useRoleList({projectId = undefined, workspaceId = undefined} = {}) {
    const [rolesList, setRolesList] = useState([]);
    const refreshRolesList = async () => {
        let rolesResponse;
        if (projectId !== undefined) {
            rolesResponse = await fetchRolesInProject(projectId);
        }
        if (workspaceId !== undefined) {
            rolesResponse = await fetchRolesInWorkspace(workspaceId);
        }
        if (projectId === undefined && workspaceId === undefined) {
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