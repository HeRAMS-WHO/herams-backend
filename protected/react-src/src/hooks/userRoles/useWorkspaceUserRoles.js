import {useEffect, useMemo, useState} from "react";
import useRoleList from "../Role/useRoleList";
import {createUserRole, fetchUsers, fetchUsersRolesInWorkspace} from "../../services/apiProxyService";
import {toastr} from "../../utils/modal";
import {__} from "../../utils/translationsUtility";

const scope = 'workspace';

function useUserRoles(workspaceId) {
    const [usersInPlatform, setUsersInPlatform] = useState([])
    const [workspaceUsers, setWorkspaceUsers] = useState([])
    const [selectedUsers, setSelectedUsers] = useState([])
    const [selectedRoles, setSelectedRoles] = useState([])
    const {rolesList} = useRoleList({workspaceId})
    const [errors, setErrors] = useState({})

    useEffect(() => {
        setSelectedRoles([])
    }, [scope])
    useEffect(() => {
        refreshUserRolesInWorkspace()
    }, [workspaceId])
    const processedRolesList = useMemo(() => {
        return rolesList.map(({id: value, name: label}) => ({value, label}))
    }, [rolesList])

    function clearInputs() {
        setSelectedUsers([])
        setSelectedRoles([])

    }

    function userRolesAddedCorrectly() {
        toastr({
            icon: 'success',
            timer: 1000,
            title: __('Users roles added successfully')
        })
    }

    function generateDataForAddingUserRolesToWorkspace() {
        return {
            users: selectedUsers.map(({value}) => value),
            roles: selectedRoles.map(({value}) => value),
            workspaces: [workspaceId],
            scope
        }
    }

    function refreshUserRolesInWorkspace() {
        fetchUsersRolesInWorkspace(workspaceId).then((response) => {
            setWorkspaceUsers(response);
        })
    }


    const validateUserRoles = () => {
        const errors = {};
        if (selectedUsers.length === 0) {
            errors.users = 'Users are required';
        }
        if (selectedRoles.length === 0) {
            errors.roles = 'Roles are required';
        }
        setErrors(errors);
        return Object.keys(errors).length === 0;
    }

    function addUserRolesToProject() {
        const data = generateDataForAddingUserRolesToWorkspace();
        if (!validateUserRoles()) {
            return;
        }
        createUserRole(data).then(() => {
            userRolesAddedCorrectly();
            clearInputs();
            refreshUserRolesInWorkspace();
        })

    }

    useEffect(() => {
        fetchUsers().then((response) => {
            const users = response.map(({id: value, email: label}) => ({value, label}))
            setUsersInPlatform(users)
        })
    }, [])

    return {
        usersInPlatform,
        selectedUsers,
        setSelectedUsers,
        errors,
        selectedRoles,
        setSelectedRoles,
        processedRolesList,
        workspaceUsers,
        addUserRolesToProject,
        refreshUserRolesInWorkspace
    }
}

export default useUserRoles;