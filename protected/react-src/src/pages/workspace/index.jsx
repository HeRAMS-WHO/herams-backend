import {__} from "../../utils/translationsUtility";
import useWorkspacesList from "../../hooks/Workspace/useWorkspacesList";
import WorkspaceIndexTableHeader from "./index/WorkspaceIndexTableHeader";
import Table from "../../components/common/table/Table";

const WorkspacesList = () => {
    const { workspacesList, isLoading } = useWorkspacesList(); // Destructure isLoading

    if (isLoading) {
        return <div>Loading...</div>; // Render a loading indicator or similar while data is being fetched
    }
    return (
        <div className="container-fluid px-2">
            <div className="row mt-2">
                <div className="col-md-12">
                    <h1 className="mt-3">
                        {__('Workspaces list')}
                    </h1>
                </div>
            </div>
            <div className="row mt-2">
                <div className="col-md-12">

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
