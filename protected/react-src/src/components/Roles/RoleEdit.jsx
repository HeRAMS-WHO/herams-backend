import {useEffect, useState} from 'react';
import { __ } from '../../utils/translationsUtility';
import {
    roleScopes,
    roleTypes
} from '../../enums/RoleEnums';

import useRole from "../../customHooks/Role/useRole";
import useRolePermissions from "../../customHooks/Role/useRolePermissions";

import FormGroup from "../form/FormGroup";
import TextInput from "../form/TextInput";
import DropdownInput from "../form/DropdownInput";
import CheckboxesGroup from "../CheckboxesGroup";

const RoleEdit = ({roleId}) => {
    const {
        roleData,
        setRoleProperty
    } = useRole(roleId);

    const {
        rolesPermissions,
        setRolesPermissions,
        updatePermissionInRole
    } = useRolePermissions(roleId);


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
                    value={roleData.role}
                    onChange={(e) => setRoleProperty('role', e.target.value)} />
            </FormGroup>
            {roleData.scope === 'project' &&
                <FormGroup label={__('Custom role project')} hasStar={true}>
                    <DropdownInput
                        className="form-control"
                        options={projects}
                        value={roleData.project}
                        onChange={(e) => setRoleProperty('project', e.target.value)} />
                </FormGroup>
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
                    onChange={(e) => { console.log(e.target.value); }} />
            </div>
            <div className="row">
                <div className="col-12">
                    <button
                        className="btn btn-secondary"
                        onClick={() => history.back()}>
                        {__('Cancel')}
                    </button>
                    <button
                        className="btn btn-primary"
                        onClick={() => updatePermissionInRole()}>
                        {__('Save')}
                    </button>
                </div>
            </div>
        </>
    );
}
export default RoleEdit;
