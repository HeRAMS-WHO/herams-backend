import {useEffect, useState} from 'react';
import { __ } from '../../utils/translationsUtility';
import {
    roleScopes,
    roleTypes
} from '../../enums/RoleEnums';
import {
    updateRoleAndPermissions
} from "../../services/apiProxyService";

import useRole from "../../customHooks/Role/useRole";
import useRolePermissions from "../../customHooks/Role/useRolePermissions";
import useProjects from "../../customHooks/Project/useProjects";

import FormGroup from "../form/FormGroup";
import TextInput from "../form/TextInput";
import DropdownInput from "../form/DropdownInput";
import CheckboxesGroup from "../CheckboxesGroup";

const RoleEdit = ({roleId = 0}) => {
    const {
        roleData,
        setRoleProperty
    } = useRole(roleId);

    const {
        rolesPermissions,
        setRolesPermissions,
        updatePermissionInRole,
        updateAllChildren
    } = useRolePermissions(roleId);
    const { projects } = useProjects();

    const updateRole = async () => {
        const filteredPermissions = rolesPermissions.filter((permission) => permission.checked);
        const data = {
            ...roleData,
            permissions: filteredPermissions
        }
        const response = await updateRoleAndPermissions(roleId, data);
        if (response.id) {
            window.location.href = '../../role';
        }
    }
    return (
        <>
            <h2>{__('Roles Edit')}</h2>
            <FormGroup label={__('Role name')} hasStar={true}>
                <TextInput
                    className="form-control"
                    value={roleData.name}
                    onChange={(e) => setRoleProperty('name', e.target.value)}
                />
            </FormGroup>
            <FormGroup label={__('Scope')} hasStar={true}>
                <DropdownInput
                    className="form-control"
                    value={roleData.scope}
                    options={roleScopes}
                    onChange={(e) => setRoleProperty('scope', e.target.value)} />
            </FormGroup>
            <FormGroup label={__('Type')} hasStar={true}>
                <DropdownInput
                    className="form-control"
                    options={roleTypes}
                    value={roleData.type}
                    onChange={(e) => setRoleProperty('type', e.target.value)} />
            </FormGroup>
            {(projects.length > 0 && roleData.scope === 'project' &&
                <FormGroup label={__('Custom role project')} hasStar={true}>
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
            <div className="row mt-4 d-flex text-right">
                <div className="col-2 offset-8">
                    <button
                        className="w-100 btn btn-secondary "
                        onClick={() => window.location.href='../../role'}>
                        {__('Cancel')}
                    </button>
                </div>
                <div className="col-2">
                    <button
                        className="w-100 btn btn-default"
                        onClick={updateRole}>
                        <i className="fa fa-save" />
                        {__('Save changes')}
                    </button>
                </div>
            </div>
        </>
    );
}
export default RoleEdit;
