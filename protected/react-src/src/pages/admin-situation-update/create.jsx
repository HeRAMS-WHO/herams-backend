import SurveyWidget from "../../components/SurveyJs/SurveyWidget"
const CreateAdminSituationUpdate = () => {
    const { hsduId } = params.value
    const url = `${window.location.origin}/facility/${hsduId}/create-admin-situation`
    return (<> <SurveyWidget url={url} /> </>)
}

export default CreateAdminSituationUpdate
