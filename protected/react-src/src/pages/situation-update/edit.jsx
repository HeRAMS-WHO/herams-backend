import SurveyWidget from "../../components/SurveyJs/SurveyWidget"
const EditSituationUpdate = () => {
    const { hsduId, updateId } = params.value
    const url = `${window.location.origin}/facility/${hsduId}/edit-situation/${updateId}`
    return (<> <SurveyWidget url={url} /> </>)
}

export default EditSituationUpdate
