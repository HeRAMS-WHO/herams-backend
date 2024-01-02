import { useState } from 'react';
import { deleteHSDU } from '../../services/apiProxyService';

function useProjectDelete() {
    const [isDeletingHSDU, setIsDeletingHSDU] = useState(false);
    const [error, setError] = useState(null);

    const HSDUDelete = async (projectId) => {
        setIsDeletingHSDU(true);
        setError(null);
        try {
            await deleteHSDU(projectId);
        } catch (error) {
            // Handle errors, such as setting error state or logging
            setError(error);
        } finally {
            setIsDeletingHSDU(false);
        }
    };

    return {
        HSDUDelete,
        isDeletingHSDU,
        error,
    };
}

export default useProjectDelete;
