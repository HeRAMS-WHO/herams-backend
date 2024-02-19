import SurveyCreatorWidget from "../../components/SurveyJs/SurveyCreatorWidget";
import { BASE_URL } from "../../services/apiProxyService";
const EditSurvey = () => {
    const { surveyID } = params.value
    const url = `${BASE_URL}/survey/${surveyID}/updateConfig`
    return (<> <SurveyCreatorWidget url={url} /> </>)
}

export default EditSurvey
