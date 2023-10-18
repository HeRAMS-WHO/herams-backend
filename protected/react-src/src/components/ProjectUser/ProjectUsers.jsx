import {useState} from "react";
import { __ } from '../../utils/translationsUtility';
import FormGroup from "../common/form/FormGroup";

const ProjectUsers = ({ projectId }) => {
    const [scope, setScope] = useState('project');
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
                        type='radio'
                        name='scope'
                        value='project'
                        checked={scope === 'project'}
                        onClick={(e) => setScope(e.target.value)} />
                        <label> {__('To Project')} </label>
                </div>
                <div className="col-md-3">
                    <input
                        type='radio'
                        name='scope'
                        value='workspace'
                        checked={scope === 'workspace'}
                        onClick={(e) => setScope(e.target.value)} />
                        <label> {__('To Workspaces')} </label>
                </div>
            </div>
            <FormGroup label={__('Users')}>
                block with users
            </FormGroup>
            { scope === 'workspace' &&
                <FormGroup label={__('Workspaces')}>
                    block with workspaces
                </FormGroup> }

        </div>
    );
}

export default ProjectUsers;