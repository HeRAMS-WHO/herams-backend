import {useEffect, useState} from "react";
import {createUserRole, fetchAUserInformation, fetchRoles, fetchUserRoles} from "../../services/apiProxyService";

const useGlobalUserRoles = ({userId}) => {
    const [userInfo, setUserInfo] = useState([])
    const [userRoles, setUserRoles] = useState([])
    const [selectedRole, setSelectedRole] = useState([])
    const [globalRoles, setGlobalRoles] = useState([]);
    const refreshUserRoles = () => {
        fetchUserRoles(userId).then((response) => {
            setUserRoles(response)
        })
    }
    const generateDataForAddingGlobalRoleToUser = () => {
        return {
            users: [userId],
            roles: selectedRole.map(({value}) => value),
            workspaces: [],
            scope: 'global',
            project_id: null,
        }
    }
    const addGlobalRoleToUser = () => {
        const data = generateDataForAddingGlobalRoleToUser()
        createUserRole(data).then(() => {
            refreshUserRoles()
            setSelectedRole([])
        })
    }
    useEffect(() => {
        fetchAUserInformation(userId).then((response) => {
            setUserInfo(response);
        })
        fetchRoles().then((response) => {
            const filteredRoles = response.filter((role) => {
                return role.scope.toLowerCase() === "global"
            }).map((role) => ({value: role.id, label: role.name}))
            setGlobalRoles(filteredRoles)
        })
        refreshUserRoles()
    }, [userId]);
    return {
        userInfo,
        userRoles,
        refreshUserRoles,
        selectedRole,
        setSelectedRole,
        globalRoles,
        addGlobalRoleToUser
    }
}

export default useGlobalUserRoles