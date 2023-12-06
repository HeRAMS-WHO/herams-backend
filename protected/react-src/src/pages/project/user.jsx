import {useId} from "react"
import {__} from '../../utils/translationsUtility';
import FormGroup from "../../components/common/form/FormGroup";
import FormButtons from "../../components/common/form/FormButtons";
import ReactTagsWrapper from "../../components/common/form/ReactTagsWrapper";
import Table from "../../components/common/table/Table";
import ProjectUserRolesTableHeader from "./user/ProjectUserRolesTableHeader";
import {deleteUserRole} from "../../services/apiProxyService";
import useProjectUserRoles from "../../hooks/userRoles/useProjectUserRoles";
import params from "../../states/params";
import info from "../../states/info";
import routeInfo from "../../states/routeInfo";
import useReloadInfo from "../../hooks/useReloadInfo";
const ProjectUserRoles = () => {
    const {projectId} = params.value;
    const labelScopeProject = useId()
    const labelScopeWorkspace = useId()
    const {
        usersInPlatform,
        selectedUsers,
        setSelectedUsers,
        workspacesInProject,
        selectedWorkspaces,
        setSelectedWorkspaces,
        selectedRoles,
        setSelectedRoles,
        scope,
        setScope,
        addUserToProject,
        projectUsers,
        filteredRolesByScope,
        refreshUserRolesInProject,
        errors
    } = useProjectUserRoles(projectId);
    const deleteYesCallback = (id) => {
        deleteUserRole(id)
            .then(() => {
                refreshUserRolesInProject()
                useReloadInfo()
            })
    }
    
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
            <FormGroup label={__('Users')} inputClassName='col-md-9' error={errors.users}>
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
            <FormGroup label={__('Workspaces')} inputClassName='col-md-9' error={errors.workspaces}>
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
            <FormGroup label={__('Roles')} inputClassName='col-md-9' error={errors.roles}>
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
            <Table
                columnDefs={ProjectUserRolesTableHeader({deleteYesCallback})}
                data={projectUsers}/>
        </div>
    );
}

export default ProjectUserRoles;