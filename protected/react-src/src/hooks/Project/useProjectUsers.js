import { useState} from "react";

const useProjectUsers = ({projectId}) => {
    const [projectUsers, setProjectUsers] = useState([]);
    const [scope, setScope] = useState('project');
    const updateProjectUsers = (projectUsers) => {
        setProjectUsers(projectUsers);
    }
    const updateScope = (scope) => {
        setScope(scope);
    }
    return {
        projectUsers,
        scope,
        updateProjectUsers,
        updateScope
    }
}
export default useProjectUsers;