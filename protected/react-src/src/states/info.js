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
        ':hsduId' : info.value?.hsdu?.id || '',
        ':hsduName' : info.value?.hsdu?.admin_data?.name || '',
        ':updateID' : info.value?.response?.id || '',
        ':pageId' : info.value?.survey?.id || '',
        ':pageName' : info.value?.survey?.name || '',
        ':hsduCount' : info.value?.workspace?.amountOfFacilitiesInWorkspace || '0',
        ':SitUpCount' : info.value?.hsdu?.dataSurveyResponseCount || '0',
        ':AdmUpCount' : info.value?.hsdu?.adminSurveyResponseCount|| '0',
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