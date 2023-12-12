import {__} from "../../utils/translationsUtility";
import useResponseList from "../../hooks/SituationUpdate/useSituationUpdate";
import SituationUpdateIndexTableHeader from "./index/SituationUpdateIndexTableHeader";
import Table from "../../components/common/table/Table";
import TableButtonWithLInk from "../../components/common/button/TableButtonWithLink";
import replaceVariablesAsText from "../../utils/replaceVariablesAsText";

const SituationUpdateList = () => {
    const { responsesList } = useResponseList()
    return (
        <div className="container-fluid px-2">
            <div className="row mt-2">
                <div className="col-md-12">
                    <h1 className="mt-3">
                        {__('Situation Update list')}
                    </h1>
                </div>
            </div>
            <div className="row mt-2">
                <div className="col-md-12">
                    <TableButtonWithLInk
                        buttons={[
                            {
                                label: __('Update Situation'),
                                class: "btn btn-default",
                                icon: "add_box",
                                url: replaceVariablesAsText( `/admin/project/:projectId/workspace/:workspaceId/HSDU/:hsduId/situation-update/create`)
                            }
                        ]}
                    />
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
