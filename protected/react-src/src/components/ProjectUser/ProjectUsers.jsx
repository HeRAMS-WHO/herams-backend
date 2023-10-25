import {useEffect, useId, useMemo, useState} from "react"
import {__} from '../../utils/translationsUtility';
import FormGroup from "../common/form/FormGroup";
import {createUserRole, fetchProjectWorkspaces, fetchUsers} from "../../services/apiProxyService";
import useRoleList from "../../hooks/Role/useRoleList";
import FormButtons from "../common/form/FormButtons";
import ReactTagsWrapper from "../common/form/ReactTagsWrapper";

const ProjectUsers = ({projectId}) => {
    const labelScopeProject = useId()
    const labelScopeWorkspace = useId()
    const [usersInPlatform, setUsersInPlatform] = useState([])
    const [projectUsers, setProjectUsers] = useState([])
    const [selectedUsers, setSelectedUsers] = useState([])
    const [workspacesInProject, setWorkspacesInProject] = useState([])
    const [selectedWorkspaces, setSelectedWorkspaces] = useState([])
    const [rolesInRoles, setRolesInProject] = useState([])
    const [selectedRoles, setSelectedRoles] = useState([])
    const [scope, setScope] = useState('project')
    const {rolesList} = useRoleList(projectId)

    useEffect(() => {
        setSelectedRoles([])
    }, [scope])

    function addUserToProject() {
        const data = {
            users: selectedUsers.map(({value}) => value),
            roles: selectedRoles.map(({value}) => value),
            workspaces: scope.toLowerCase() !== 'project' ? selectedWorkspaces.map(({value}) => value) : [],
            scope,
            project_id: projectId,
        }
        createUserRole(data).then((response) => {
            console.log(response)
        })

    }

    const filteredRolesByScope = useMemo(() => {
        return rolesList.filter((role) => role.scope.toLowerCase() === scope.toLowerCase())
            .map(({id: value, name: label}) => ({value, label}))
    }, [rolesList, scope])


    useEffect(() => {
        fetchUsers().then((response) => {
            const users = response.map(({id: value, email: label}) => ({value, label}))
            setUsersInPlatform(users)
        })
    }, [])

    useEffect(() => {
        fetchProjectWorkspaces(projectId).then((response) => {
            const workspaces = response.map(({id: value, name: label}) => ({value, label}))
            setWorkspacesInProject(workspaces)
        })
    }, [projectId])


    return (
        <div className="container-fluid px-2">
            <div className="row mt-2">
                <div className="col-md-12">
                    <h1 className="mt-3">
                        {__('Add new users')}
                    </h1>
                </div>
            </div>
            <div className="row mt-2">
                <div className="col-md-3">
                    <input
                        id={labelScopeProject}
                        type='radio'
                        name='scope'
                        value='project'
                        checked={scope === 'project'}
                        onChange={(e) => setScope(e.target.value)}/>
                    <label htmlFor={labelScopeProject}> {__('To Project')} </label>
                </div>
                <div className="col-md-3">
                    <input
                        id={labelScopeWorkspace}
                        type='radio'
                        name='scope'
                        value='workspace'
                        checked={scope === 'workspace'}
                        onChange={(e) => setScope(e.target.value)}/>
                    <label htmlFor={labelScopeWorkspace}> {__('To Workspaces')} </label>
                </div>
            </div>
            <FormGroup label={__('Users')} inputClassName='col-md-9'>
                <ReactTagsWrapper
                    labelText={__("Select users")}
                    placeholderText={selectedUsers.length === 0 ? __("Select users") : ''}
                    state={selectedUsers}
                    setter={setSelectedUsers}
                    suggestions={usersInPlatform}
                    noOptionsText={__("No matching users")}
                />
            </FormGroup>
            {scope === 'workspace' &&
            <FormGroup label={__('Workspaces')} inputClassName='col-md-9'>
                <ReactTagsWrapper
                    labelText={__("Select workspaces")}
                    placeholderText={selectedWorkspaces.length === 0 ? __("Select workspaces") : ''}
                    state={selectedWorkspaces}
                    setter={setSelectedWorkspaces}
                    suggestions={workspacesInProject}
                    noOptionsText={__("No matching workspaces")}
                />
            </FormGroup>
            }
            <FormGroup label={__('Roles')} inputClassName='col-md-9'>
                <ReactTagsWrapper
                    labelText={__("Type for search")}
                    placeholderText={selectedRoles.length === 0 ? __("Type for search") : ''}
                    state={selectedRoles}
                    setter={setSelectedRoles}
                    suggestions={filteredRolesByScope}/>
            </FormGroup>
            <div className="d-flex gap-1 mt-4 place-end">
                <FormButtons
                    buttons={[
                        {
                            label: __('Save changes'),
                            class: "btn btn-default w200px",
                            onClick: addUserToProject
                        }
                    ]}
                />
            </div>
        </div>
    );
}

export default ProjectUsers;