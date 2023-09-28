import {useEffect, useState} from "react";
import {
    fetchPermissions,
    fetchRolePermissions
} from "../../services/apiProxyService";
const useRolePermissions = (roleId) => {
    const [rolesPermissions, setRolesPermissions] = useState([]);
    useEffect( () => {
        async function fetchData() {
            const permissionsResponse = await fetchPermissions();
            const permissionsInRole = await fetchRolePermissions(roleId);
            const tempPermissions = permissionsResponse.map((permission) => ({
                value: permission.code,
                label: permission.name,
                parent: permission.parent,
                checked: !!permissionsInRole.find((rolePermission) => rolePermission.permission_code === permission.code),
            }));
            setRolesPermissions(tempPermissions);
        }
        fetchData()
            .then(() => {})
            .catch((error) => {
                console.log(error);
            })
    }, [roleId]);
    const updatePermissionInRole = (permissionCode, checked) => {
        const tempPermissions = [...rolesPermissions];
        const permissionIndex = tempPermissions.findIndex((permission) => permission.value === permissionCode);
        tempPermissions[permissionIndex].checked = checked;
        setRolesPermissions(tempPermissions);
    }
    return {
        rolesPermissions,
        setRolesPermissions,
        updatePermissionInRole
    }
}
export default useRolePermissions;