import {__} from "../../utils/translationsUtility";
import useProjects from "../../hooks/Project/useProjects";
import ProjectIndexTableHeader from "./index/ProjectIndexTableHeader";
import Table from "../../components/common/table/Table";
import ActionOnHeaderLists from "../../components/common/ActionOnHeaderLists";
import replaceVariablesAsText from "../../utils/replaceVariablesAsText";

const ProjectList = () => {
    const { projects } = useProjects()

    return (
        <div className="container-fluid px-2">
            <div className="row mt-2">
                <div className="col-md-6">
                    <h1 className="mt-3">
                        {__('Projects')}
                    </h1>
                </div>
                <div className="col-md-6">
                    <ActionOnHeaderLists
                        label={__('Create project')}
                        permissions={['CREATE_PROJECT']}
                        url={replaceVariablesAsText( `/admin/project/create`)}/>
                </div>
            </div>
            <Table
                deleteYesCallback={() => {}}
                columnDefs={ProjectIndexTableHeader()}
                data={projects}/>
        </div>
    );
}

export default ProjectList;
