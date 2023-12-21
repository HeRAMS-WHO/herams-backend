import React from "react";
import SurveyFormWidget from "../../components/SurveyJs/SurveyFormWidget";

const ProjectSettings = () => {
    return (<> <SurveyFormWidget url={`${window.location.origin}/project/create`} /> </>)
};
export default ProjectSettings;
