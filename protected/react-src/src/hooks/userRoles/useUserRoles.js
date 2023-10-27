import {useEffect, useMemo, useState} from "react";
import useRoleList from "../Role/useRoleList";
import {
    createUserRole,
    fetchProjectWorkspaces,
    fetchUserRolesInProject,
    fetchUsers
} from "../../services/apiProxyService";
import {toastr} from "../../utils/modal";
import {__} from "../../utils/translationsUtility";

function useUserRoles(projectId) {
    const [usersInPlatform, setUsersInPlatform] = useState([])
    const [projectUsers, setProjectUsers] = useState([])
    const [selectedUsers, setSelectedUsers] = useState([])
    const [workspacesInProject, setWorkspacesInProject] = useState([])
    const [selectedWorkspaces, setSelectedWorkspaces] = useState([])
    const [selectedRoles, setSelectedRoles] = useState([])
    const [scope, setScope] = useState('project')
    const {rolesList} = useRoleList(projectId)

    useEffect(() => {
        setSelectedRoles([])
    }, [scope])

    function clearInputs() {
        setSelectedUsers([])
        setSelectedRoles([])
        setSelectedWorkspaces([])
    }

    function userRolesAddedCorrectly() {
        toastr({
            icon: 'success',
            timer: 1000,
            title: __('Users roles added successfully')
        })
    }

    function generateDataForAddingUserRolesToProject() {
        const data = {
            users: selectedUsers.map(({value}) => value),
            roles: selectedRoles.map(({value}) => value),
            workspaces: scope.toLowerCase() !== 'project' ? selectedWorkspaces.map(({value}) => value) : [],
            scope,
            project_id: projectId,
        }
        return data;
    }

    function addUserRolesToProject() {
        const data = generateDataForAddingUserRolesToProject();
        createUserRole(data).then(() => {
            userRolesAddedCorrectly();
            clearInputs();
        })

    }

    const filteredRolesByScope = useMemo(() => {
        return rolesList.filter((role) => role.scope.toLowerCase() === scope.toLowerCase())
            .map(({id: value, name: label}) => ({value, label}))
    }, [rolesList, scope])


    useEffect(() => {
        fetchUsers().then((response) => {
            const users = response.map(({id: value, email: label}) => ({value, label}))
            setUsersInPlatform(users)
        })
    }, [])

    useEffect(() => {
        fetchProjectWorkspaces(projectId).then((response) => {
            const workspaces = response.map(({id: value, name: label}) => ({value, label}))
            setWorkspacesInProject(workspaces)
        })
    }, [projectId])

    useEffect(() => {
        fetchUserRolesInProject(projectId).then((response) => {
            setProjectUsers(response)
        })
    }, [projectId])
    return {
        usersInPlatform,
        selectedUsers,
        setSelectedUsers,
        workspacesInProject,
        selectedWorkspaces,
        setSelectedWorkspaces,
        selectedRoles,
        setSelectedRoles,
        scope,
        setScope,
        projectUsers,
        addUserToProject: addUserRolesToProject,
        filteredRolesByScope
    };
}

export default useUserRoles;