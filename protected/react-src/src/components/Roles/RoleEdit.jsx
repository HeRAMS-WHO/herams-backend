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
            <h1>{__('Roles Edit')}</h1>
            {JSON.stringify(roleData)}
            <FormGroup label={__('Role name')}>
                <TextInput
                    className="form-control"
                    value={roleData.name}
                    onChange={(e) => setRoleProperty('name', e.target.value)}
                />
            </FormGroup>
            <FormGroup label={__('Scope')}>
                <DropdownInput
                    className="form-control"
                    value={roleData.scope}
                    options={roleScopes}
                    onChange={(e) => setRoleProperty('scope', e.target.value)} />
            </FormGroup>
            <FormGroup label={__('Type')}>
                <DropdownInput
                    className="form-control"
                    options={roleTypes}
                    value={roleData.role}
                    onChange={(e) => setRoleProperty('role', e.target.value)} />
            </FormGroup>
            {roleData.scope === 'project' &&
                <FormGroup label={__('Custom role project')}>
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
            <h4 className="mt-3">
                {__('Permissions')}
            </h4>
            <div className="row">
                <CheckboxesGroup
                    options={rolesPermissions}
                    onChange={(e) => { console.log(e.target.value); }} />
            </div>
        </>
    );
}
export default RoleEdit;
