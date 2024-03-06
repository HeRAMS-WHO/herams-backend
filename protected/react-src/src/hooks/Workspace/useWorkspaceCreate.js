import { useEffect, useState } from "react"
import { fetchCreateWorkspace } from "../../services/apiProxyService"

function useWorkspaceCreate(params) {
    const [titles, setTitles] = useState({})
    useEffect(() => {
        const titlesObject = {}
        info?.value?.project?.languages?.forEach((language) => {
            titlesObject[language] = ''
        })
        setTitles(titlesObject)
    }, [info.value, specialVariables.value])
    const createWorkspace = ({titles: title}) => {
        const data = {
            projectId: info.value.project.id,
            title
        }
        fetchCreateWorkspace(data)
            .then((response) => response.json())
            .then((response) => {
                console.log(response)
            });
    }
    return {
        createWorkspace,
        titles,
        setTitles
    }
}

export default useWorkspaceCreate