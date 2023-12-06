import {
    fetchProject,
    fetchUser,
    fetchWorkspace,
    fetchRole,
    fetchHsdu
} from "../services/apiProxyService";

const fetchProjectInfo = (projectId) => {
    if (projectId) {
        return fetchProject(projectId);
    }
    return Promise.resolve(null);
}

const fetchUserInfo = (userId) => {
    if (userId) {
        return fetchUser(userId);
    }
    return Promise.resolve(null);
}

const fetchWorkspaceInfo = (workspaceId) => {
    if (workspaceId) {
        return fetchWorkspace(workspaceId);
    }
    return Promise.resolve(null);
}

const fetchRoleInfo = (roleId) => {
    if (roleId) {
        return fetchRole(roleId);
    }
    return Promise.resolve(null);
}

const fetchHsduInfo = (hsduId) => {
    if (hsduId) {
        return fetchHsdu(hsduId);
    }
    return Promise.resolve(null);
}

const reloadInfo = ({info, params}) => {
    const {
        projectId = null,
        userId = null,
        workspaceId = null,
        roleId = null,
        hsduId = null
    } = params.value;

    Promise.all([
        fetchProjectInfo(projectId),
        fetchUserInfo(userId),
        fetchWorkspaceInfo(workspaceId),
        fetchRoleInfo(roleId),
        fetchHsduInfo(hsduId)
    ]).then(([project, user, workspace, role, hsdu]) => {
        info.value = {
            ...info.value,
            project,
            user,
            workspace,
            role,
            hsdu
        };
    });
}

/*const reloadInfo = ({info, params}) => {
    const {
        projectId = null,
        userId = null,
        workspaceId = null,
        roleId = null,
        hsduId = null
    } = params.value;
    if (projectId) {
        fetchProject(projectId).then((project) => {
            const value = info.value;
            info.value = {
                ...value,
                project
            }
        })
    }
    else {
        const value = info.value;
        info.value = {
            ...value,
            project: null
        }
    }
    if (userId) {
        fetchUser(userId).then((user) => {
            const value = info.value;
            info.value = {
                ...value,
                user
            }
        })
    }
    else {
        const value = info.value;
        info.value = {
            ...value,
            user: null
        }
    }
    if (workspaceId) {
        fetchWorkspace(workspaceId).then((workspace) => {
            const value = info.value;
            info.value = {
                ...value,
                workspace
            }
        })
    }
    else {
        const value = info.value;
        info.value = {
            ...value,
            workspace: null
        }
    }
    if (roleId) {
        fetchRole(roleId).then((role) => {
            const value = info.value;
            info.value = {
                ...value,
                role
            }
        })
    }
    else {
        const value = info.value;
        info.value = {
            ...value,
            role: null
        }
    }
    if (hsduId) {
        fetchHsdu(hsduId).then((hsdu) => {
            const value = info.value;
            info.value = {
                ...value,
                hsdu
            }
        })
    }
    else {
        const value = info.value;
        info.value = {
            ...value,
            hsdu: null
        }
    }
}
*/
export default reloadInfo;