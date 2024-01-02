import SurveyWidget from "../../components/SurveyJs/SurveyWidget"
const EditAdminSituationUpdate = () => {
    const { hsduId, updateId } = params.value
    const url = `${window.location.origin}/facility/${hsduId}/edit-admin-situation/${updateId}`
    return (<> <SurveyWidget url={url} /> </>)
}

export default EditAdminSituationUpdate
