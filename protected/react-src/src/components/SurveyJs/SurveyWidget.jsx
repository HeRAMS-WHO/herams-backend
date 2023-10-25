import React, { useEffect, useState } from 'react';
import 'survey-core/defaultV2.min.css';
import 'survey-creator-core/survey-creator-core.min.css';
import { applySurveyConfigurations } from './custom/survey-modifications';
import { applyHSDUStateQuestion } from './custom/HSDUStateQuestion';
import { applyFacilityTypeQuestion } from './custom/FacilityTypeQuestion';
import * as SurveyKnockout from "survey-react-ui";

applySurveyConfigurations();
applyHSDUStateQuestion();
applyFacilityTypeQuestion();

function SurveyWidget(props) {
    const [survey, setSurvey] = useState(null);
    const [data, setData] = useState(null);

    // Similar to componentDidMount and componentDidUpdate
    useEffect(() => {
        const fetchData = async () => {
            const config = props.surveySettings;

            let surveyStructure = config.structure;
            if (config.localeEndpoint) {
                const locales = await fetchWithCsrf(config.localeEndpoint, null, 'get');
                surveyStructure.locales = locales.languages;
            }
            const surveyInstance = new SurveyKnockout.Survey(surveyStructure);
            surveyInstance.mode = config.displayMode ? "display" : "edit";

            if (config.dataUrl && !window.shouldUpdateSurveyData) {
                let fetchedData = await fetchWithCsrf(config.dataUrl, null, 'GET');
                for (const pathElement of config.dataPath) {
                    fetchedData = fetchedData[pathElement];
                }
                if (props.haveToDeleteDate) {
                    delete fetchedData['HSDU_DATE'];
                    delete fetchedData['SITUATION_DATE'];
                }
                setData({ ...fetchedData, ...config.data });
            } else {
                setData(config.data);
            }

            // Similar logic can be added for `survey.onComplete` and `survey.onServerValidateQuestions`
            // You can use surveyInstance.onComplete.add(...) and so on.

            setSurvey(surveyInstance);
        };

        fetchData();
    }, []);  // This means run once after the component is mounted.

    useEffect(() => {
        if (survey && data) {
            survey.data = data;
            survey.render(props.surveySettings.elementId);
            window.survey = survey;
        }
    }, [survey, data]);

    return (
        <div>
            { !survey ? 'Loading...' : null }
            {/* Render the survey configuration for debugging purposes */}
            <pre>{JSON.stringify(props.surveySettings, null, 2)}</pre>
            <div id={props.surveySettings.elementId}></div>
        </div>
    );
}

function fetchWithCsrf(url, data, method) {
    // Implement this function similar to Herams.fetchWithCsrf
    // You can use fetch or Axios or any other method to make the request
}

export default SurveyWidget;
