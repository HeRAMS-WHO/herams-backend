import React, { useEffect, useState } from 'react';
import 'survey-core/defaultV2.min.css';
import 'survey-creator-core/survey-creator-core.min.css';
import { Model } from "survey-core";
import { Survey as SurveyComponent } from "survey-react-ui";
import { get as getWithCsrf, post as postWithCsrf } from '../../services/httpMethods'; // Adjust the import path
import { applyLocalizableProjectTextQuestion } from './custom/LocalizableProjectTextQuestion';
import { applyProjectVisibilityQuestion } from './custom/ProjectVisibilityQuestion';


applyLocalizableProjectTextQuestion();
applyProjectVisibilityQuestion();


function SurveyFormWidget(props) {
    const [survey, setSurvey] = useState(null);
    const config = JSON.parse(atob(props.surveySettings));

    useEffect(() => {
        const initSurvey = async () => {
            const surveyInstance = new Model(config.structure);

            if (config.localeEndpoint) {
                const locales = await getWithCsrf(config.localeEndpoint);
                surveyInstance.locales = locales.languages;
            }

            if (config.dataUrl) {
                const fetchedData = await getWithCsrf(config.dataUrl);
                surveyInstance.data = { ...fetchedData, ...config.extraData };
            }

            surveyInstance.onComplete.add(async (sender, options) => {
                try {
                    await postWithCsrf(config.submissionUrl, { data: sender.data });
                    if (config.redirectUrl) {
                        window.location.assign(config.redirectUrl);
                    }
                } catch (error) {
                    console.error("Error submitting survey data:", error);
                }
            });

            surveyInstance.onServerValidateQuestions.add(async (sender, options) => {
                try {
                    const validationResponse = await postWithCsrf(config.validationUrl, { data: sender.data });
                    // Handle validation logic here
                } catch (error) {
                    console.error("Error during server validation:", error);
                }
            });

            setSurvey(surveyInstance);
        };

        initSurvey();
    }, [props.surveySettings]);

    return <div id={config.elementId}>
        {survey && <SurveyComponent model={survey} />}
    </div>
}

export default SurveyFormWidget;
