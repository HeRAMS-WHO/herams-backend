import { useEffect, useState } from "react"
import { get } from "../../services/httpMethods"
import SurveyWidget from "../../components/SurveyJs/SurveyWidget"
const CreateWorkspace = () => {
    const { hsduId } = params.value
    const url = `${window.location.origin}/facility/${hsduId}/update-situation`
    return (<> <SurveyWidget url={url} /> </>)
}

export default CreateWorkspace
