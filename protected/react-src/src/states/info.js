import { signal } from "@preact/signals-react";

const info = signal({
    project: {},
    workspace: {},
    role: {},
    user: {},
    facility: {},
    survey: {},
    response: {}
})
const specialVariables = signal({})
const reloadSpecialVariables = ({language, state}) => {
    const variablesMap = {
        ':workspace' : info.value?.workspace?.title?.[language] 
            || info.value?.workspace?.title?.['en'] 
            ||'workspace',
        ':workspaceName' : info.value?.workspace?.title?.[language] 
            || info.value?.workspace?.title?.['en'] 
            ||'workspace',
        ':project' : info.value?.project?.i18n?.title?.[language] 
            || info.value?.project?.i18n?.title?.['en'] 
            ||'project',
        ':projectName' : info.value?.project?.i18n?.title?.[language] 
            || info.value?.project?.i18n?.title?.['en'] 
            ||'project',
        ':userName' : info.value?.user?.name || '',
        ':userEmail' : info.value?.user?.email || '',
        ':userId' : info.value?.user?.id || '',
        ':projectId' : info.value?.project?.id || '',
        ':workspaceId' : info.value?.workspace?.id || '',
        ':hsduId' : info.value?.facility?.id || '',
        ':hsduName' : info.value?.facility?.name || '',
        ':updateID' : info.value?.response?.id || '',
        ':pageId' : info.value?.survey?.id || '',
        ':pageName' : info.value?.survey?.name || '',
        ':hsduCount' : info.value?.workspace?.amountOfFacilitiesInWorkspace || '0',
        ':SitUpCount' : info.value?.workspace?.facilities?.filter(f => f.type === 'SITUATION').length || '',
        ':AdmUpCount' : info.value?.workspace?.facilities?.filter(f => f.type === 'ADMINISTRATIVE').length || '',
        ':workspaceUsersCount' : info.value?.workspace?.userAmountInWorkspace || '0',
        ':projectUsersCount' : info.value?.project?.projectUsersCount || 0
        
        
    }
    const keys = Object.keys(variablesMap).sort((a, b) => b.length - a.length)
    state.value = {
        translations: keys.reduce((acc, key) => {
            acc[key] = variablesMap[key]
            return acc
            }, {}),
        keys: keys 
    }
}
export default info
export { specialVariables, reloadSpecialVariables }