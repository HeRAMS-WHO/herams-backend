import React, { useEffect, useState } from 'react';
import 'survey-core/defaultV2.min.css';
import 'survey-creator-core/survey-creator-core.min.css';
import { Model } from "survey-core";
import { Survey } from "survey-react-ui";
import {fetchWithCsrf} from '../../services/httpMethods';  // Adjust the import path
import { post as postWithCsrf } from '../../services/httpMethods';  // Adjust the import path

function SurveyWidget(props) {
    const [survey, setSurvey] = useState(null);
    const [surveys, setSurveys] = useState([]);

    // Custom hook or function to parse configuration
    const parseConfig = (configEncoded) => {
        try {
            return JSON.parse(atob(configEncoded));
        } catch (error) {
            console.error("Error parsing survey configuration:", error);
            return null; // Or handle this case as per your requirements
        }
    }

    useEffect(() => {
        const config = parseConfig(props.surveySettings);
        if (!config) return;
        const haveToDeleteDate = props?.haveToDeleteDate ?? 0; //todo: to be fixed

        const setupSurvey = async () => {
            let surveyStructure = config.structure;

            //this is working
            if (config.localeEndpoint) {
                try {
                    const locales = await fetchWithCsrf(config.localeEndpoint, null, 'get');
                    surveyStructure.locales = locales.languages;
                } catch (error) {
                    console.error("Error fetching locales:", error);
                }
            }

            const surveyInstance = new Model(surveyStructure);
            surveyInstance.mode = config.displayMode ? "display" : "edit";
            console.log('displayMOdeJs', surveyInstance.mode);

            let restartWithFreshData
            let waitForDataPromise

            if (config.dataUrl) {
                restartWithFreshData = async () => {
                    console.log("Clearing survey", config.dataUrl);
                    surveyInstance.clear()
                    let data = await fetchWithCsrf(config.dataUrl, null, 'GET');
                    for (const pathElement of config.dataPath) {
                        data = data[pathElement]
                    }
                    try {
                        if (haveToDeleteDate){
                            delete (data['HSDU_DATE']);
                            delete(data['SITUATION_DATE']);
                        }
                        surveyInstance.data = { ...data, ...config.data }
                    } catch (error) {
                        surveyInstance.data = {};
                        console.warn("Fallback to setting individual values", error);
                        for(const [key, value] of Object.entries({ ...data, ...config.data })) {
                            try {
                                console.log("Setting", key, value);
                                surveyInstance.setValue(key, value);
                            } catch (error) {
                                console.warn("Failed to set", key, value, error);
                            }
                        }
                    }
                    return surveyInstance.data;
                }
                waitForDataPromise = restartWithFreshData();

            } else {
                waitForDataPromise = new Promise((resolve, reject) => {
                    surveyInstance.data = config.data
                    resolve(config.data)
                })

            }

            let currentSurveys = [...surveys, surveyInstance];
            setSurveys(currentSurveys);
            surveyInstance.surveyShowDataSaving = true;

            // Event handlers for submission and validation
            if (config.submissionUrl) {
                surveyInstance.onComplete.add(async (sender, options) => {
                    options.showDataSaving('Uploading data');
                    try {
                        const json = await fetchWithCsrf(config.submissionUrl, {
                            ...(config.extraData ?? {}),
                            data: sender.data
                        })
                        options.showDataSavingSuccess('Data saved');
                        //const notification = window.Herams.notifySuccess("Data saved", 'center');
                        if (config.redirectUrl) {
                            //await notification
                            window.location.assign(config.redirectUrl);
                        } else if (restartWithFreshData) {
                            return restartWithFreshData()
                        }

                    } catch(error) {
                        if (Object.getPrototypeOf(error).name === 'ValidationError') {
                            options.showDataSavingError(error.message + ': ' + JSON.stringify(error.errors));
                        } else {
                            options.showDataSavingError(error.message);
                        }


                    }
                });
            }

            if (config.validationUrl) {
                surveyInstance.onServerValidateQuestions.add(async (sender, options) => {

                    try {
                        let validationData = {
                            ...(config.extraData ?? {}),
                            data: sender.data
                        }
                        console.log('php json pre validation', validationData);
                        const json = await fetchWithCsrf(config.validationUrl, validationData);
                        console.log('php json validation response', json);
                        let visibleError = false
                        for (const [attribute, errors] of Object.entries(json.errors)) {
                            options.errors[attribute] = errors.join(', ');
                            visibleError = visibleError
                                || typeof options.data[attribute] !== 'undefined'
                                || currentSurveys[0].currentPage.getQuestionByName(attribute)?.isVisible
                        }

                        // If the error is not visible, add it to all questions
                        if (!visibleError) {
                            for (const question of sender.currentPage.questions) {
                                for (const [attribute, errors] of Object.entries(json.errors)) {
                                    options.errors[question.name] = errors.join(', ');
                                }
                            }
                        }

                    } catch (error) {
                        // This is a big error, add it to all questions on the page.
                        for (const question of sender.currentPage.questions) {
                            options.errors[question.name] = error.message
                        }
                    }
                    options.complete();
                });
            }
            const data = await waitForDataPromise

            setSurvey(surveyInstance);

        };

        setupSurvey();
    }, [props.surveySettings]);


    return (
        <div>
            {survey && <Survey model={survey} />}
        </div>
    );
}

export default SurveyWidget;
