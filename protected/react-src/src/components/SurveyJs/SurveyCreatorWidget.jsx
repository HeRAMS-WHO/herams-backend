import React, { useEffect, useState } from "react";
import "survey-core/defaultV2.min.css";
import "survey-creator-core/survey-creator-core.min.css";
import { SurveyCreatorComponent, SurveyCreator } from "survey-creator-react";

import { applySurveyConfigurations } from './custom/survey-modifications';
import {applyHSDUStateQuestion} from "./custom/HSDUStateQuestion";
import {applyFacilityTypeQuestion} from "./custom/FacilityTypeQuestion";
import {createInCollectionWithCsrf, fetchWithCsrf} from "../../services/httpMethods";

applySurveyConfigurations();
applyHSDUStateQuestion();
applyFacilityTypeQuestion();


const SurveyCreatorWidget = (props) => {
    const [creator, setCreator] = useState(null);
    useEffect(() => {
        const decodedConfig = atob(props.surveySettings);
        const config = JSON.parse(decodedConfig);

        const updateSurvey = async (saveNo, callback) => {
            try {
                const response = await fetchWithCsrf(config.dataUrl, {config: surveyCreator.JSON}, 'PUT');
                console.warn('response', response);
                callback(saveNo, true);
            } catch (e) {
                console.error(e);
                callback(saveNo, false);
            }
        };

        const getData = async () => {
            return await fetchWithCsrf(config.dataUrl, null, 'GET');
        }

        const createSurvey = async (saveNo, callback) => {
            try {
                const surveyUrl = await createInCollectionWithCsrf(config.createUrl, {config: surveyCreator.JSON});
                const id = surveyUrl.match(/\d+/)[0];

                let newUrl = config.updateUrl.replace('10101010', id);
                window.location.replace(newUrl);

                callback(saveNo, true);
            } catch (e) {
                console.error(e);
                callback(saveNo, false);
            }
        };

        const surveyCreator = new SurveyCreator(config.creatorOptions);
        surveyCreator.toolbox.allowExpandMultipleCategories = true
        surveyCreator.haveCommercialLicense = false


        //Refresh survey and reset property grid to let it handle onShowingProperty event
        surveyCreator.JSON = {};

        surveyCreator.saveSurveyFunc = (saveNo, callback) => config.dataUrl ? updateSurvey(saveNo, callback) : createSurvey(saveNo, callback);

        if (config.dataUrl) {
            getData().then(data => surveyCreator.JSON = data.config);
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
