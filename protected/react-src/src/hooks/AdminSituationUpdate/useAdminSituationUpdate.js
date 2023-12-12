import { useEffect, useState } from "react";
import {fetchAdminResponses} from "../../services/apiProxyService";

const adminResponsesList = () => {
    const { projectId } = params.value;
    const [adminResponsesList, setAdminResponsesList] = useState([]);
    const [isLoading, setIsLoading] = useState(true); // Add a loading state

    useEffect(() => {
        fetchAdminResponses(projectId).then((response) => {
            setAdminResponsesList(response);
            setIsLoading(false); // Set loading to false once data is fetched
        })
    }, [projectId]) // Include projectId in dependency array

    return {
        adminResponsesList,
        isLoading // Return the loading state
    }
}

export default adminResponsesList;
