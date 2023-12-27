import SurveyCreatorWidget from "../../components/SurveyJs/SurveyCreatorWidget";
const EditSurvey = () => {
    const { surveyID } = params.value
    const url = `${window.location.origin}/survey/${surveyID}/update`
    return (<> <SurveyCreatorWidget url={url} /> </>)
}

export default EditSurvey
