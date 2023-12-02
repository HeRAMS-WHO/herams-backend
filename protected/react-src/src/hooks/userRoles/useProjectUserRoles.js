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
import { autoReloadSpecialVariables } from "../../states/info";
import useReloadSpecialVariables from "../useReloadSpecialVariables";
import useReloadInfo from "../useReloadInfo";
function useProjectUserRoles(projectId) {
    const [usersInPlatform, setUsersInPlatform] = useState([])
    const [projectUsers, setProjectUsers] = useState([])
    const [selectedUsers, setSelectedUsers] = useState([])
    const [workspacesInProject, setWorkspacesInProject] = useState([])
    const [selectedWorkspaces, setSelectedWorkspaces] = useState([])
    const [selectedRoles, setSelectedRoles] = useState([])
    const [scope, setScope] = useState('project')
    const {rolesList} = useRoleList({projectId})
    const [errors, setErrors] = useState({})

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
        return {
            users: selectedUsers.map(({value}) => value),
            roles: selectedRoles.map(({value}) => value),
            workspaces: scope.toLowerCase() !== 'project' ? selectedWorkspaces.map(({value}) => value) : [],
            scope,
            project_id: projectId,
        }
    }

    const validateUserRoles = () => {
        const errors = {};
        if (selectedUsers.length === 0) {
            errors.users = 'Users are required';
        }
        if (selectedRoles.length === 0) {
            errors.roles = 'Roles are required';
        }
        if (scope.toLowerCase() === 'workspace' && selectedWorkspaces.length === 0) {
            errors.workspaces = 'Workspaces are required';
        }
        setErrors(errors);
        return Object.keys(errors).length === 0;
    }

    function addUserRolesToProject() {
        const data = generateDataForAddingUserRolesToProject();
        if (!validateUserRoles()) {
            return;
        }
        createUserRole(data).then(() => {
            userRolesAddedCorrectly()
            clearInputs()
            refreshUserRolesInProject()
            useReloadInfo()
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

    const refreshUserRolesInProject = () => {
        fetchUserRolesInProject(projectId).then((response) => {
            setProjectUsers(response)
        })
    }
    useEffect(() => {
        refreshUserRolesInProject()
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
        filteredRolesByScope,
        refreshUserRolesInProject,
        errors
    };
}

export default useProjectUserRoles;