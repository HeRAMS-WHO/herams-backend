import FormGroup from "../../components/common/form/FormGroup"
import TextInput from "../../components/common/form/TextInput"
import FormButtons from "../../components/common/form/FormButtons"
import useWorkspaceCreate from "../../hooks/Workspace/useWorkspaceCreate"
const WorkspaceCreate = () => {
    const {
        createWorkspace,
        titles,
        setTitles
    } = useWorkspaceCreate()
    return (<>
        <div>
           <form className="w-100">
                    {Object.keys(titles).map((language) => {
                        return (
                            <div key={language}>
                                <FormGroup
                                    label={locales?.value?.find((element) => element.value === language)?.name}
                                    inputClassName='col-md-6'>
                                    <TextInput
                                        className="form-control"
                                        onChange={(e) => setTitles({...titles, [language]: e.target.value})}
                                        value={titles[language]} />
                                </FormGroup>
                            </div>
                        )
                    })}
                    <div className="d-flex gap-1 mt-4 place-end">
                    <FormButtons
                        buttons={[
                            {
                                label: __('Save changes'),
                                class: "btn btn-default w200px",
                                onClick: () => createWorkspace({titles})
                            }
                        ]}
                    />
                </div>
            </form>
        </div>
    </>)
}

export default WorkspaceCreate
