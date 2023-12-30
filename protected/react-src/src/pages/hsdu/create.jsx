import SurveyWidget from "../../components/SurveyJs/SurveyWidget"
const CreateHSDU = () => {
    const { workspaceId } = params.value
    const url = `${window.location.origin}/facility/create?workspaceId=${workspaceId}`
    return (<> <SurveyWidget url={url} /> </>)
}

export default CreateHSDU
