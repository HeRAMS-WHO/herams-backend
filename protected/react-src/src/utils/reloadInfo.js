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


export default reloadInfo;