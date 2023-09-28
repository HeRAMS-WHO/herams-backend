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
        project: '',
        role: '',
        createdDate: '',
        createdBy: '',
        lastModifiedDate: '',
        lastModifiedBy: '',
    });
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
                project: roleResponse.project,
                role: roleResponse.role,
                createdDate: roleResponse.created_date,
                createdBy: roleResponse.creatorUserInfo?.name,
                lastModifiedDate: roleResponse.last_modified_date,
                lastModifiedBy: roleResponse.updaterUserInfo?.name
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
        setRoleProperty
    }
}

export default useRole;
