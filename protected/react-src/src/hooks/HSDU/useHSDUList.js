import { useEffect, useState } from "react";
import {fetchFacilities, fetchHsdu} from "../../services/apiProxyService";

const useHSDUList = () => {
    const { workspaceId } = params.value;
    const [HSDUList, seHSDUList] = useState([])
    const [isLoading, setIsLoading] = useState(true); // Add a loading state

    useEffect(() => {
        fetchFacilities(workspaceId).then((response) => {
            seHSDUList(response);
            setIsLoading(false); // Set loading to false once data is fetched
        })
    }, [workspaceId]) // Include projectId in dependency array

    return {
        HSDUList,
        isLoading // Return the loading state
    }
}

export default useHSDUList;

