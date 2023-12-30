import { useState } from 'react';
import { deleteProject } from '../../services/apiProxyService';

function useProjectDelete() {
    const [isDeletingProject, setIsDeletingProject] = useState(false);
    const [error, setError] = useState(null);

    const projectDelete = async (projectId) => {
        setIsDeletingProject(true);
        setError(null);
        try {
            await deleteProject(projectId);
        } catch (error) {
            // Handle errors, such as setting error state or logging
            setError(error);
        } finally {
            setIsDeletingProject(false);
        }
    };

    return {
        projectDelete,
        isDeletingProject,
        error,
    };
}

export default useProjectDelete;
