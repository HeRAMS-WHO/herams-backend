import { __ } from '../../utils/translationsUtility';
import {
    roleScopes,
    roleTypes
} from '../../enums/RoleEnums';
import {
    updateRoleAndPermissions
} from "../../services/apiProxyService";
import styles from './RoleEdit.module.css';
import useRole from "../../hooks/Role/useRole";
import useRolePermissions from "../../hooks/Role/useRolePermissions";
import useProjects from "../../hooks/Project/useProjects";

import FormGroup from "../common/form/FormGroup";
import TextInput from "../common/form/TextInput";
import DropdownInput from "../common/form/DropdownInput";
import CheckboxesGroup from "../common/form/CheckboxesGroup";
import FormButtons from "../common/form/FormButtons";

const RoleEdit = ({roleId = 0}) => {
    const {
        roleData,
        setRoleProperty,
        validateRole,
        roleErrors
    } = useRole(roleId);

    const {
        rolesPermissions,
        setRolesPermissions,
        updatePermissionInRole,
        updateAllChildren
    } = useRolePermissions(roleId);
    const { projects } = useProjects();

    const updateRole = async () => {
        if (!validateRole()) {
            return;
        }
        const filteredPermissions = rolesPermissions.filter((permission) => permission.checked);
        const data = {
            ...roleData,
            permissions: filteredPermissions
        }

        const response = await updateRoleAndPermissions(roleId, data);
        if (response.ok) {
            window.location.href = '../../role';
        }
    }
    return (
        <>
            {Number(roleId) !== 0 ? <h2>{__('Roles Edit')}</h2> : <h2>{__('Roles Create')}</h2>}
            <FormGroup label={__('Role name')} hasStar={true} error={roleErrors?.name}>
                <TextInput
                    className="form-control"
                    value={roleData.name}
                    onChange={(e) => setRoleProperty('name', e.target.value)}
                />
            </FormGroup>
            <FormGroup label={__('Scope')} hasStar={true} error={roleErrors?.scope}>
                <DropdownInput
                    className="form-control"
                    value={roleData.scope}
                    options={roleScopes}
                    onChange={(e) => setRoleProperty('scope', e.target.value)} />
            </FormGroup>
            <FormGroup label={__('Type')} hasStar={true} error={roleErrors?.type}>
                <DropdownInput
                    className="form-control"
                    options={roleTypes}
                    value={roleData.type}
                    onChange={(e) => setRoleProperty('type', e.target.value)} />
            </FormGroup>
            {(projects.length > 0 && roleData.scope === 'project' &&
                <FormGroup label={__('Custom role project')} hasStar={true} error={roleErrors?.projectId}>
                    <DropdownInput
                        className="form-control"
                        options={projects}
                        value={roleData.projectId}
                        onChange={(e) => setRoleProperty('projectId', e.target.value)} />
                </FormGroup>) || null
            }
            <br/>
            <br/>
            <FormGroup label={__('Created on')}>
                <TextInput
                    className="form-control"
                    value={roleData.createdDate}
                    disabled={true}
                />
            </FormGroup>
            <FormGroup label={__('Created by')}>
                <TextInput
                    className="form-control"
                    value={roleData.createdBy}
                    disabled={true}
                />
            </FormGroup>
            <FormGroup label={__('Last modified on')}>
                <TextInput
                    className="form-control"
                    value={roleData.lastModifiedDate}
                    disabled={true}
                />
            </FormGroup>
            <FormGroup label={__('Last modified by')}>
                <TextInput
                    className="form-control"
                    value={roleData.lastModifiedBy}
                    disabled={true}
                />
            </FormGroup>
            <h2 className="mt-3">
                {__('Permissions')}
            </h2>
            <div className="container-scrollable-700x400">
                <CheckboxesGroup
                    options={rolesPermissions}
                    changeChildren={updateAllChildren}
                    onChange={updatePermissionInRole} />
            </div>
            <div className={`${styles.dFlex} ${styles.gap1} ${styles.mt4} ${styles.placeEnd}`}>
                    <FormButtons
                        buttons={[
                            {
                                label: __("Cancel"),
                                class: `btn btn-secondary ${styles.w200px}`,
                                onClick: () => window.location.href='../../role'
                            }
                        ]}
                    />
                    <FormButtons
                        buttons={[
                            {
                                label:  __('Save changes'),
                                class: `btn btn-default ${styles.w200px}`,
                                onClick: updateRole
                            }
                        ]}
                    />
            </div>
        </>
    );
}
export default RoleEdit;
