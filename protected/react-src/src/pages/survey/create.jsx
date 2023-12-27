import SurveyCreatorWidget from "../../components/SurveyJs/SurveyCreatorWidget";
const CreateSurvey = () => {
    const url = `${window.location.origin}/survey/create`
    return (<> <SurveyCreatorWidget url={url} /> </>)
}

export default CreateSurvey
