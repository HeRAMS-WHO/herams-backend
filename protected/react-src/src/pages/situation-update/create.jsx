import SurveyWidget from "../../components/SurveyJs/SurveyWidget"
const CreateSituationUpdate = () => {
    const { hsduId } = params.value
    const url = `${window.location.origin}/facility/${hsduId}/update-situation`
    return (<> <SurveyWidget url={url} /> </>)
}

export default CreateSituationUpdate
