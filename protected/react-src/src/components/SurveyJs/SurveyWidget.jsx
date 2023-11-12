import React, { useEffect, useState } from 'react';
import 'survey-core/defaultV2.min.css';
import 'survey-creator-core/survey-creator-core.min.css';
// import * as SurveyKnockout from "survey-react-ui";
import {Model} from "survey-core";
import {Survey} from "survey-react-ui";

function SurveyWidget(props) {
    const [survey, setSurvey] = useState(null);
    const [data, setData] = useState(null);
    const [config, setConfig] = useState({});

    console.log('test');
    // Similar to componentDidMount and componentDidUpdate
    useEffect(() => {
        console.log('test');
        const fetchData = async () => {
            const surveySettings = atob(props.surveySettings);
            console.log('t2',JSON.parse(surveySettings));
            setConfig(JSON.parse(surveySettings))
            const surveyConfig = JSON.parse(surveySettings);
            let surveyStructure = surveyConfig.structure;
            // if (surveyConfig.localeEndpoint) {
            //     const locales = await fetchWithCsrf(surveyConfig.localeEndpoint, null, 'get');
            //     console.log('locales',locales);
            //     surveyStructure.locales = locales.languages;
            // }

            const surveyInstance = new Model(surveyStructure);

            // const surveyInstance = new SurveyKnockout.Survey(surveyStructure);
            // surveyInstance.mode = surveyConfig.displayMode ? "display" : "edit";

            // if (surveyConfig.dataUrl && !window.shouldUpdateSurveyData) {
            //     let fetchedData = await fetchWithCsrf(surveyConfig.dataUrl, null, 'GET');
            //     for (const pathElement of surveyConfig.dataPath) {
            //         fetchedData = fetchedData[pathElement];
            //     }
            //     // if (props.haveToDeleteDate) {
            //     //     delete fetchedData['HSDU_DATE'];
            //     //     delete fetchedData['SITUATION_DATE'];
            //     // }
            //     setData({ ...fetchedData, ...surveyConfig.data });
            // } else {
            //     setData(surveyConfig.data);
            // }

            // Similar logic can be added for `survey.onComplete` and `survey.onServerValidateQuestions`
            // You can use surveyInstance.onComplete.add(...) and so on.

            setSurvey(surveyInstance);
        };

        fetchData();
    }, [props.surveySettings]);  // This means run once after the component is mounted.

     return <div>
         {survey && <Survey model={survey} />}
     </div>
}

function fetchWithCsrf(url, data, method) {
    // Implement this function similar to Herams.fetchWithCsrf
    // You can use fetch or Axios or any other method to make the request
}

export default SurveyWidget;
