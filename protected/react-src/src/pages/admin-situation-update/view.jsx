import { useEffect, useState } from "react"
import { get } from "../../services/httpMethods"
import SurveyWidget from "../../components/SurveyJs/SurveyWidget"
const ViewAdminSituationUpdate = () => {
    const { hsduId,updateId } = params.value
    const url = `${window.location.origin}/facility/${hsduId}/view-admin-situation/${updateId}`
    return (<> <SurveyWidget url={url} /> </>)

}

export default ViewAdminSituationUpdate
