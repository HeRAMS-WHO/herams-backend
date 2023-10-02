import { useEffect, useState } from "react";
import "survey-core/defaultV2.min.css";
import "survey-creator-core/survey-creator-core.min.css";
import { SurveyCreatorComponent, SurveyCreator } from "survey-creator-react";
import { Serializer } from "survey-core";

// import {Model} from "survey-core";
// import './surveyjs/survey-modifications';
// import './surveyjs/HSDUStateQuestion';
// import './surveyjs/LocalizableProjectTextQuestion';
// import { registerProjectVisibility } from './surveyjs/registerProjectVisibility'
// import './surveyjs/ProjectVisibilityQuestion';
import {registerProjectVisibility} from "./surveyjs/ProjectVisibilityQuestion";

registerProjectVisibility();
const SurveyCreatorWidget = (props) => {
    const [creator, setCreator] = useState(null);

    useEffect(() => {
        const decodedConfig = atob(props.config);
        const config = JSON.parse(decodedConfig);

        const updateSurvey = async (saveNo, callback) => {
            try {
                const response = await fetch(config.dataUrl, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ config: creator.JSON })
                });
                console.warn('response', response);
                callback(saveNo, true);
            } catch (e) {
                console.error(e);
                callback(saveNo, false);
            }
        };

        const createSurvey = async (saveNo, callback) => {
            try {
                const response = await fetch(config.createUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ config: creator.JSON })
                });
                const surveyUrl = await response.text();
                config.dataUrl = surveyUrl;
                const id = surveyUrl.match(/\d+/)[0];
                // history.replaceState({}, '', config.updateUrl.replace('10101010', id));
                callback(saveNo, true);
            } catch (e) {
                console.error(e);
                callback(saveNo, false);
            }
        };

        const surveyCreator = new SurveyCreator(config.creatorOptions);

        surveyCreator.saveSurveyFunc = (saveNo, callback) => config.dataUrl ? updateSurvey(saveNo, callback) : createSurvey(saveNo, callback);

        console.log(config);
        if (config.dataUrl) {
            fetch(config.dataUrl)
                .then(response => response.json())
                .then(data => {
                    surveyCreator.JSON = data.config;
                });
        }

        setCreator(surveyCreator);

        // Cleanup on unmount
        return () => {
            setCreator(null);
        };
    }, [props.config]);

    if (!creator) return null;

    return (
        <SurveyCreatorComponent creator={creator} />
    );
};

export default SurveyCreatorWidget;
