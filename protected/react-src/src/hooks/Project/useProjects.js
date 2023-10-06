import {useEffect, useState} from "react";
import { fetchProjects } from "../../services/apiProxyService";

const useProjects = () => {
    const [projects, setProjects] = useState([]);

    useEffect(() => {
        (async () => {
            const projectList = await fetchProjects();
            const processedProjects = projectList.map(project => {
                const primaryLanguage = project.primary_language;
                const label = project.i18n['title'][primaryLanguage];
                const value = project.id;
                return {label, value};
            })
            setProjects(processedProjects);
        })();
    }, []);

    return {
        projects
    };
}
export default useProjects;