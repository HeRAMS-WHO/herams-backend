import { useEffect, useState } from "react";
import {fetchResponses} from "../../services/apiProxyService";

const useResponseList = () => {
    const { hsduId } = params.value;
    const [responsesList, setResponsesList] = useState([]);
    const [isLoading, setIsLoading] = useState(true); // Add a loading state

    useEffect(() => {
        fetchResponses(hsduId).then((response) => {
            setResponsesList(response);
            setIsLoading(false); // Set loading to false once data is fetched
        })
    }, [hsduId]) // Include projectId in dependency array

    return {
        responsesList,
        isLoading // Return the loading state
    }
}

export default useResponseList;
