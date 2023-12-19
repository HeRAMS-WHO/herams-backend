import SurveyFormWidget from "../../components/SurveyJs/SurveyFormWidget";
const CreateWorkspace = () => {
    const { projectId } = params.value
    const url = `${window.location.origin}/workspace/create?project_id=${projectId}`
    return (<> <SurveyFormWidget url={url} /> </>)
}

export default CreateWorkspace
