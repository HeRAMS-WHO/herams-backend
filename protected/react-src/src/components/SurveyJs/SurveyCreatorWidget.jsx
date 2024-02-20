import { useEffect, useState } from "react";
import { BASE_URL } from "../../services/apiProxyService";
import "survey-core/defaultV2.min.css";
import "survey-creator-core/survey-creator-core.min.css";
import { SurveyCreatorComponent, SurveyCreator } from "survey-creator-react";
import "survey-core/survey.i18n.js";
import { surveyLocalization } from "survey-core";

import { applySurveyConfigurations } from './custom/survey-modifications';
import {applyHSDUStateQuestion} from "./custom/HSDUStateQuestion";
import {applyFacilityTypeQuestion} from "./custom/FacilityTypeQuestion";
import {createInCollectionWithCsrf, fetchWithCsrf, get} from "../../services/httpMethods";
import { useNavigate } from "../common/router";

applySurveyConfigurations();
applyHSDUStateQuestion();
applyFacilityTypeQuestion();

const getSurveyConfig = (surveyID = null) => {
    if (!surveyID) {
        return {
            settings: {
                'creatorOptions': {
                    'showState': 'true',
                    'showTranslationTab': 'true'
                },
                'createUrl': `${BASE_URL}/survey`,
                'dataUrl': null,
                'elementId': 'w0',
                'updateUrl': null
            }
        };
    }

    const settings = {
        settings: {
            'creatorOptions': {
                'showState': 'true',
                'showTranslationTab': 'true'
            },
            'createUrl': `${BASE_URL}/survey`,
            'dataUrl': `${BASE_URL}/survey/${surveyID}`,
            'elementId': 'w0',
            'updateUrl': `${BASE_URL}/survey/${surveyID}/update`
        }
    };
    
    return settings;
    
}
const SurveyCreatorWidget = () => {
    const [creator, setCreator] = useState(null);
    const [config, setSurveyConfig] = useState(null);
    const [surveyID, setSurveyID] = useState(params.value?.surveyID ); 
    surveyLocalization.supportedLocales = [];

    useEffect(() => {
        const {settings} = getSurveyConfig(surveyID);
        setSurveyConfig(settings)
    }, [surveyID])

    useEffect(() => {

        if (!config) return;

        const updateSurvey = async (saveNo, callback) => {
            try {
                const response = await fetchWithCsrf(config.dataUrl, {config: surveyCreator.JSON}, 'PUT');
                //window.location.href = (window.location.origin + '/admin/survey/');
                callback(saveNo, true);
            } catch (e) {
                console.error(e);
                callback(saveNo, false);
            }
        };

        const getData = async () => {
            return await get(config.dataUrl);
        };

        const createSurvey = async (saveNo, callback) => {
            try {
                const { id } = await createInCollectionWithCsrf(config.createUrl, {config: surveyCreator.JSON});                
                window.location.href=id;
                //callback(saveNo, true);
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
        surveyCreator.haveCommercialLicense = true;

        surveyCreator.saveSurveyFunc = (saveNo, callback) => config.dataUrl ? updateSurvey(saveNo, callback) : createSurvey(saveNo, callback);

        if (config.dataUrl) {
            getData().then(data => surveyCreator.JSON = data.config);
        }

        setCreator(surveyCreator);

        // Cleanup on unmount
        return () => {
            setCreator(null);
        };
    }, [config]);

    if (!creator) return null;

    return (
        <SurveyCreatorComponent creator={creator} />
    );
};

export default SurveyCreatorWidget;
