import { useEffect, useState } from "react";
import {fetchResponses} from "../../services/apiProxyService";

const useResponseList = () => {
    const { projectId } = params.value;
    const [responsesList, setResponsesList] = useState([]);
    const [isLoading, setIsLoading] = useState(true); // Add a loading state

    useEffect(() => {
        fetchResponses(projectId).then((response) => {
            setResponsesList(response);
            setIsLoading(false); // Set loading to false once data is fetched
        })
    }, [projectId]) // Include projectId in dependency array

    return {
        responsesList,
        isLoading // Return the loading state
    }
}

export default useResponseList;
