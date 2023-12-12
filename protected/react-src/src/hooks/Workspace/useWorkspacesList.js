import { useEffect, useState } from "react";
import { fetchProjectWorkspaces } from "../../services/apiProxyService";

const useWorkspacesList = () => {
    const { projectId } = params.value;
    const [workspacesList, setWorkspacesList] = useState([]);
    const [isLoading, setIsLoading] = useState(true); // Add a loading state

    useEffect(() => {
        fetchProjectWorkspaces(projectId).then((response) => {
            setWorkspacesList(response);
            setIsLoading(false); // Set loading to false once data is fetched
        })
    }, [projectId]) // Include projectId in dependency array

    return {
        workspacesList,
        isLoading // Return the loading state
    }
}

export default useWorkspacesList;
