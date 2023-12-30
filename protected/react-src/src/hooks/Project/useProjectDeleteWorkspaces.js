import { useState } from 'react';
import { deleteProjectWorkspaces } from '../../services/apiProxyService';

function useProjectDeleteWorkspaces() {
    const [isDeletingWorkspaces, setIsDeletingWorkspaces] = useState(false);
    const [error, setError] = useState(null);

    const deleteWorkspaces = async (projectId) => {
        setIsDeletingWorkspaces(true);
        setError(null);

        try {
            await deleteProjectWorkspaces(projectId);
        } catch (error) {
            // Handle errors, such as setting error state or logging
            setError(error);
        } finally {
            setIsDeletingWorkspaces(false);
        }
    };

    return {
        deleteWorkspaces,
        isDeletingWorkspaces,
        error,
    };
}

export default useProjectDeleteWorkspaces;
