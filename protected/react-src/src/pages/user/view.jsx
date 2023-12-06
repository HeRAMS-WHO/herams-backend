import useGlobalUserRoles from "../../hooks/userRoles/useGlobalUserRoles";
import {BasicUserInfo, TitleGlobalUserRoles} from "./view/GlobalUserRolesParts";
import Table from "../../components/common/table/Table";
import GlobalUserRolesTableHeader from "./view/GlobalUserRolesTableHeader";
import FormButtons from "../../components/common/form/FormButtons";
import {__} from "../../utils/translationsUtility";
import ReactTagsWrapper from "../../components/common/form/ReactTagsWrapper";
import FormGroup from "../../components/common/form/FormGroup";
import {deleteUserRole} from "../../services/apiProxyService";
const GlobalUserRoles = () => {
    const {userId} = params.value;
    const {
        globalRoles,
        userInfo,
        refreshUserRoles,
        userRoles,
        selectedRole,
        setSelectedRole,
        addGlobalRoleToUser
    } = useGlobalUserRoles({userId})
    const deleteYesCallback = (id) => {
        deleteUserRole(id).then(() => {
            refreshUserRoles()
        })
    }
    return (
        <div className="container-fluid px-2">
            <TitleGlobalUserRoles userInfo={userInfo}/>
            <BasicUserInfo userInfo={userInfo}/>
            <div className="row mt-2">
                <form className="w-100">
                    <div>
                        <FormGroup label={__('Global roles')} inputClassName='col-md-9'>
                            <ReactTagsWrapper
                                labelText={__("Type to search...")}
                                placeholderText={selectedRole.length === 0 ? __("Type to search...") : ''}
                                state={selectedRole}
                                setter={setSelectedRole}
                                suggestions={globalRoles}
                                noOptionsText={__("No matching global roles")}
                            />
                        </FormGroup>
                    </div>
                </form>
            </div>
            <div className="d-flex gap-1 mt-4 place-end">
                <FormButtons
                    buttons={[
                        {
                            label: __('Save changes'),
                            class: "btn btn-default w200px",
                            onClick: addGlobalRoleToUser
                        }
                    ]}
                />
            </div>
            <Table
                data={userRoles}
                refreshData={refreshUserRoles}
                columnDefs={GlobalUserRolesTableHeader({deleteYesCallback})} />
        </div>
    );
}

export default GlobalUserRoles;