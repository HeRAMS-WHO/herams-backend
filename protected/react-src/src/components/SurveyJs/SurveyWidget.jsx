import React, { useEffect, useState } from 'react';
import 'survey-core/defaultV2.min.css';
import 'survey-creator-core/survey-creator-core.min.css';
import { Model } from "survey-core";
import { Survey } from "survey-react-ui";
import { get as getWithCsrf } from '../../services/httpMethods';  // Adjust the import path

function SurveyWidget(props) {
    const [survey, setSurvey] = useState(null);

    useEffect(() => {
        const fetchData = async () => {
            const surveySettings = atob(props.surveySettings);
            const surveyConfig = JSON.parse(surveySettings);
            let surveyStructure = surveyConfig.structure;

            if (surveyConfig.localeEndpoint) {
                const locales = await getWithCsrf(surveyConfig.localeEndpoint);
                surveyStructure.locales = locales.languages;
            }

            const surveyInstance = new Model(surveyStructure);
            surveyInstance.mode = surveyConfig.displayMode ? "display" : "edit";

            if (surveyConfig.dataUrl && !window.shouldUpdateSurveyData) {
                let fetchedData = await getWithCsrf(surveyConfig.dataUrl);
                for (const pathElement of surveyConfig.dataPath) {
                    fetchedData = fetchedData[pathElement];
                }
                surveyInstance.data = { ...fetchedData, ...surveyConfig.data };
            } else {
                surveyInstance.data = surveyConfig.data;
            }

            // Add event handlers like onComplete, onServerValidateQuestions, etc.

            setSurvey(surveyInstance);
        };

        fetchData();
    }, [props.surveySettings]);

    return <div>
        {survey && <Survey model={survey} />}
    </div>
}

export default SurveyWidget;
