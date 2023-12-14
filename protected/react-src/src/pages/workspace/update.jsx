import { useEffect, useState } from "react"
import FormGroup from "../../components/common/form/FormGroup"
import TextInput from "../../components/common/form/TextInput"
import info from "../../states/info"
const WorkspaceUpdate = () => {
    const [titles, setTitles] = useState({})
    useEffect(() => {
        const titlesObject = {}
        info?.value?.project?.languages?.forEach((language) => {
            titlesObject[language] = info?.value?.workspace?.title[language] || ''
        })
        setTitles(titlesObject)
    }, [info.value])
    return (<>
        <div>
            <h1>{replaceVariablesAsText('Workspace Update')}</h1>
           <form className="w-100">
                    {Object.keys(titles).map((language) => {
                        return (
                            <div key={language}>
                                <FormGroup label={locales?.value?.find((element) => element.value === language)?.name} inputClassName='col-md-9'>
                                    <TextInput 
                                        className="form-control"
                                        onChange={(e) => setTitles({...titles, [language]: e.target.value})}
                                        value={titles[language]} />
                                </FormGroup>
                            </div>
                        )
                    })}
                    
            </form> 
        </div>
    </>)
}

export default WorkspaceUpdate