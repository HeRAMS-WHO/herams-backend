import { useEffect, useState } from "react";
import { fetchProjects } from "../../services/apiProxyService";

const useProjects = () => {
    const [projects, setProjects] = useState([]);

    useEffect(() => {
        (async () => {
            const projectList = await fetchProjects();
            setProjects(projectList);
        })();
    }, []);

    return {projects};
}

export default useProjects;
