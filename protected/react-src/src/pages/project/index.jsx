import {__} from "../../utils/translationsUtility";
import useProjects from "../../hooks/Project/useProjects";
import ProjectIndexTableHeader from "./index/ProjectIndexTableHeader";
import Table from "../../components/common/table/Table";

const ProjectIndex = () => {
    const { projectList } = useProjects()
    return (
        <div className="container-fluid px-2">
            <div className="row mt-2">
                <div className="col-md-12">
                    <h1 className="mt-3">
                        {__('Project list')}
                    </h1>
                </div>
            </div>
            <div className="row mt-2">
                <div className="col-md-12">

                </div>
            </div>
            <Table
                deleteYesCallback={() => {}}
                columnDefs={ProjectIndexTableHeader()}
                data={projectList}/>
        </div>
    );
}

export default ProjectIndex;