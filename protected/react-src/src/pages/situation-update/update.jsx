import { useEffect, useState } from "react"
import { get } from "../../services/httpMethods"
import SurveyWidget from "../../components/SurveyJs/SurveyWidget"
const UpdateSituationUpdate = () => {
    const { hsduId,  } = params.value
    const [settings, setSettings] = useState(null)
    const [haveToDelete, setHaveToDelete] = useState(false)
    
    const url = `${window.location.origin}/facility/${hsduId}/update-situation`
    useEffect(() => {
        get(url).then((response) => {
            setSettings(response.settings)
            setHaveToDelete(response.haveToDelete)
        })
    }, [])
    return (<>
            {settings && <SurveyWidget 
                surveySettings={settings}
                haveToDelete={haveToDelete} /> }
        </>
    )
}

export default UpdateSituationUpdate