import {__} from '../../utils/translationsUtility';
import FormGroup from "../../components/common/form/FormGroup";
import FormButtons from "../../components/common/form/FormButtons";
import ReactTagsWrapper from "../../components/common/form/ReactTagsWrapper";
import Table from "../../components/common/table/Table";
import {deleteUserRole} from "../../services/apiProxyService";
import useWorkspaceUserRoles from "../../hooks/userRoles/useWorkspaceUserRoles";
import WorkspaceUserRolesTableHeader from "./WorkspaceUserRolesTableHeader";

const WorkspaceUserRoles = () => {
    const {workspaceId} = params.value
    const {
        usersInPlatform,
        errors,
        selectedUsers,
        setSelectedUsers,
        selectedRoles,
        setSelectedRoles,
        processedRolesList,
        workspaceUsers,
        refreshUserRolesInWorkspace,
        addUserRolesToProject
    } = useWorkspaceUserRoles(workspaceId);
    const deleteYesCallback = (id) => {
        deleteUserRole(id)
            .then(() => {
                refreshUserRolesInWorkspace()
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
            <FormGroup label={__('Roles')} inputClassName='col-md-9' error={errors.roles}>
                <ReactTagsWrapper
                    labelText={__("Type for search")}
                    placeholderText={selectedRoles.length === 0 ? __("Type for search") : ''}
                    state={selectedRoles}
                    setter={setSelectedRoles}
                    suggestions={processedRolesList}/>
            </FormGroup>
            <div className="d-flex gap-1 mt-4 place-end">
                <FormButtons
                    buttons={[
                        {
                            label: __('Save changes'),
                            class: "btn btn-default w200px",
                            onClick: addUserRolesToProject
                        }
                    ]}
                />
            </div>
            <Table
                columnDefs={WorkspaceUserRolesTableHeader({deleteYesCallback})}
                data={workspaceUsers}/>
        </div>
    );
}

export default WorkspaceUserRoles;