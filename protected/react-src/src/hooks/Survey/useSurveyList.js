import {useEffect, useState} from "react";
import {fetchSurveys} from "../../services/apiProxyService";

const useSurveyList = () => {
    const [surveys, setSurveys] = useState([])
    const refreshSurveys = () => {
        fetchSurveys().then((response) => {
            setSurveys(response)
        })
    }
    useEffect(() => {
        refreshSurveys()
    }, [])
    return {
        surveys,
        refreshSurveys
    }
}

export default useSurveyList
