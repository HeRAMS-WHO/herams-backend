import {__} from "../../utils/translationsUtility";
import useResponseList from "../../hooks/SituationUpdate/useSituationUpdate";
import SituationUpdateIndexTableHeader from "./index/SituationUpdateIndexTableHeader";
import Table from "../../components/common/table/Table";
import replaceVariablesAsText from "../../utils/replaceVariablesAsText";
import ActionOnHeaderLists from "../../components/common/ActionOnHeaderLists";

const SituationUpdateList = () => {
    const { responsesList } = useResponseList()
    return (
        <div className="container-fluid px-2">
            <div className="row mt-2">
                <div className="col-md-6">
                    <h1 className="mt-3">
                        {__('Situation Update list')}
                    </h1>
                </div>
                <div className="col-md-6">
                    <ActionOnHeaderLists
                        label={__('Update Situation')}
                        permissions={['WORKSPACE_CREATE']}
                        url={replaceVariablesAsText( `/admin/project/:projectId/workspace/:workspaceId/HSDU/:hsduId/situation-update/create`)}/>
                </div>
            </div>
            <Table
                deleteYesCallback={() => {}}
                columnDefs={SituationUpdateIndexTableHeader()}
                data={responsesList}/>
        </div>
    );
}

export default SituationUpdateList;
