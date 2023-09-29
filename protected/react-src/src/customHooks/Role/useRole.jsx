import {
    useState,
    useEffect
} from 'react';
import {fetchRole} from "../../services/apiProxyService";

function useRole(roleId = null){
    const [roleData, setRoleData] = useState({
        id: roleId,
        name: '',
        scope: '',
        projectId: '',
        type: '',
        createdDate: '',
        createdBy: '',
        lastModifiedDate: '',
        lastModifiedBy: '',
    });
    const [roleErrors, setError] = useState({});
    const validateRole = () => {
        const errors = {};
        if (!roleData.name) {
            errors.name = 'Role name is required';
        }

        if (!roleData.scope || !['global', 'project', 'workspace'].includes(roleData.scope)) {
            errors.scope = 'Role scope is required and must be one of: global, project, workspace';
        }

        if (!roleData.type || !['standard', 'custom'].includes(roleData.type)) {
            errors.type = 'Role type is required and must be one of: standard, custom';
        }

        if (roleData.scope === 'project' && !roleData.projectId) {
            errors.projectId = 'Project is required when scope is project';
        }
        setError(errors);
        return Object.keys(errors).length === 0;
    }

    useEffect(() => {
        if (!roleId) {
            return;
        }
        async function fetchData() {
            const roleResponse = await fetchRole(roleId);
            const roleData = {
                id: roleResponse.id,
                name: roleResponse.name,
                scope: roleResponse.scope,
                projectId: roleResponse.project_id || '',
                type: roleResponse.type || '',
                createdDate: roleResponse.created_date,
                createdBy: roleResponse.creatorUserInfo?.name || '',
                lastModifiedDate: roleResponse.last_modified_date,
                lastModifiedBy: roleResponse.updaterUserInfo?.name || ''
            }
            setRoleData(roleData);
        }
        fetchData()
            .then(() => {})
            .catch((error) => {
                console.log(error);
            })
    }, [roleId]);
    const setRoleProperty = (property, value) => {
        setRoleData({
            ...roleData,
            [property]: value
        });
    }
    return {
        roleData,
        setRoleProperty,
        validateRole,
        roleErrors
    }
}

export default useRole;
