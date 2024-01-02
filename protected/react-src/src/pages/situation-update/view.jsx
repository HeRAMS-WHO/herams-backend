import SurveyWidget from "../../components/SurveyJs/SurveyWidget"
const ViewSituationUpdate = () => {
    const { hsduId, updateId } = params.value
    const url = `${window.location.origin}/facility/${hsduId}/view-situation/${updateId}`
    return (<> <SurveyWidget url={url} /> </>)

}

export default ViewSituationUpdate
