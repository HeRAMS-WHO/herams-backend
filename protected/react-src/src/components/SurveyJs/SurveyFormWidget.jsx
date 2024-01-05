import React, { useEffect, useState } from 'react';
import 'survey-core/defaultV2.min.css';
import 'survey-creator-core/survey-creator-core.min.css';
import { Model } from "survey-core";
import { Survey as SurveyComponent } from "survey-react-ui";
import {fetchWithCsrf, get} from '../../services/httpMethods';
import replaceVariablesAsText from "../../utils/replaceVariablesAsText"; // Adjust the import path

function SurveyFormWidget({url}) {
    const [survey, setSurvey] = useState(null);
    const [surveys, setSurveys] = useState([]);
    const [surveySettings, setSurveySettings] = useState(null);

    // Custom hook or function to parse configuration
    const parseConfig = (configEncoded) => {
        try {
            return JSON.parse(configEncoded);
        } catch (error) {
            console.error("Error parsing survey configuration:", error);
            return null; // Or handle this case as per your requirements
        }
    }

    useEffect(() => {
        get(url).then((response) => {
            setSurveySettings(response.settings)
        })
    }, [url])

    useEffect(() => {
        const config = parseConfig(surveySettings);
        if (!config) return;
        const initSurvey = async () => {
            let surveyStructure = config.structure;

            if (config.localeEndpoint) {
                try {
                    const locales = await fetchWithCsrf(config.localeEndpoint, null, 'get');
                    surveyStructure.locales = locales.languages;
                } catch (error) {
                    console.error("Error fetching locales:", error);
                }
            }

            const surveyInstance = new Model(surveyStructure);

            let restartWithFreshData
            let waitForDataPromise

            if (config.dataUrl) {
                restartWithFreshData = async () => {
                    console.log("Clearing survey", config.dataUrl);
                    surveyInstance.clear()
                    const data = await fetchWithCsrf(config.dataUrl, null, 'GET');
                    console.log(surveyInstance.data)
                    try {
                        data.projectvisibility = data.visibility

                        //survey.data = data
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
                    //return survey.data;
                    console.log(surveyInstance.data)
                }
                waitForDataPromise = restartWithFreshData();

            }

            let currentSurveys = [...surveys, surveyInstance];
            setSurveys(currentSurveys);
            surveyInstance.surveyShowDataSaving = true;
            if (config.submissionUrl) {
                surveyInstance.onComplete.add(async (sender, options) => {
                    options.showDataSaving('Uploading data');
                    try {
                        await fetchWithCsrf(config.submissionUrl, {
                            data: {
                                ...(config.extraData),
                                ...sender.data
                            }
                        })
                        options.showDataSavingSuccess('Data saved');
                        const notification = window.Herams.notifySuccess("Data saved", 'center');
                        if (config.redirectUrl) {
                            await notification
                            useNavigate()(replaceVariablesAsText(config.redirectUrl));
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
                        const json = await fetchWithCsrf(config.validationUrl, {
                            data: {
                                ...(config.extraData),
                                ...sender.data
                            }

                        });
                        let visibleError = false
                        console.log(json, options.data);
                        for (const [attribute, errors] of Object.entries(json.errors)) {
                            options.errors[attribute] = errors.join(', ');
                            visibleError = visibleError
                                || typeof options.data[attribute] !== 'undefined'
                                || surveys[0].currentPage.getQuestionByName(attribute)?.isVisible
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

        initSurvey();
    }, [surveySettings]);

    return <div>
        {survey && <SurveyComponent model={survey} />}
    </div>
}

export default SurveyFormWidget;
