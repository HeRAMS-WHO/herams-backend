import { useEffect, useState } from "react"
import { fetchUpdateWorkspace } from "../../services/apiProxyService"

function useWorkspaceUpdate(params) {
    const [titles, setTitles] = useState({})
    useEffect(() => {
        const titlesObject = {}
        info?.value?.project?.languages?.forEach((language) => {
            titlesObject[language] = info?.value?.workspace?.title[language] || ''
        })
        setTitles(titlesObject)
    }, [info.value, specialVariables.value])
    const updateWorkspace = ({titles: title, workspaceId : id}) => {
        const data = { 
            data : {
                title,
                id
            }
        }
        fetchUpdateWorkspace({id, data}).then((response) => {
            goToParent()
        });
    }
    return {
        updateWorkspace,
        titles,
        setTitles
    }
}

export default useWorkspaceUpdate