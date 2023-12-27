import {useEffect, useState} from "react";
import {fetchSurveys} from "../../services/apiProxyService";

const useSurveyList = () => {
    const [surveys, setSurveys] = useState([])
    useEffect(() => {
        fetchSurveys().then((response) => {
            setSurveys(response)
        })
    }, [])
    return {
        surveys
    }
}

export default useSurveyList
