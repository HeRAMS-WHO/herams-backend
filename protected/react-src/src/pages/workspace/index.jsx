import {__} from "../../utils/translationsUtility";
import useWorkspacesList from "../../hooks/Workspace/useWorkspacesList";
import WorkspaceIndexTableHeader from "./index/WorkspaceIndexTableHeader";
import Table from "../../components/common/table/Table";
import ActionOnHeaderLists from "../../components/common/ActionOnHeaderLists";
import replaceVariablesAsText from "../../utils/replaceVariablesAsText";

const WorkspacesList = () => {
    const { workspacesList, isLoading } = useWorkspacesList(); // Destructure isLoading

    if (isLoading) {
        return <div>Loading...</div>; // Render a loading indicator or similar while data is being fetched
    }
    return (
        <div className="container-fluid px-2">
            <div className="row mt-2">
                <div className="col-md-6">
                    <h1 className="mt-3">
                        {__('Workspaces list')}
                    </h1>
                </div>
                <div className="col-md-6">
                    <ActionOnHeaderLists
                        label={'Create Workspace'}
                        permissions={['WORKSPACE_CREATE']}
                        url={replaceVariablesAsText('/admin/project/:projectId/workspace/create')}/>
                </div>
            </div>
            <Table
                deleteYesCallback={() => {}}
                columnDefs={WorkspaceIndexTableHeader()}
                data={workspacesList}/>
        </div>
    );
}

export default WorkspacesList;
