import SurveyWidget from "../../components/SurveyJs/SurveyWidget"
const EditAdminSituationUpdate = () => {
    const { hsduId } = params.value
    const url = `${window.location.origin}/facility/${hsduId}/update-situation`
    return (<> <SurveyWidget url={url} /> </>)
}

export default EditAdminSituationUpdate
