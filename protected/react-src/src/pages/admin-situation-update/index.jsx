import {__} from "../../utils/translationsUtility";
import useAdminSituationUpdate from "../../hooks/AdminSituationUpdate/useAdminSituationUpdate";
import AdminSituationUpdateIndexTableHeader from "./index/AdminSituationUpdateIndexTableHeader";
import Table from "../../components/common/table/Table";
import ActionOnHeaderLists from "../../components/common/ActionOnHeaderLists";
import replaceVariablesAsText from "../../utils/replaceVariablesAsText";

const AdminSituationUpdateList = () => {
    const { adminResponsesList } = useAdminSituationUpdate()
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
                        label={__('Update HSDU Info')}
                        permissions={['HSDU_ADMIN_CREATE']}
                        url={replaceVariablesAsText( `/admin/project/:projectId/workspace/:workspaceId/HSDU/:hsduId/admin-update/create`)}/>
                </div>
            </div>
            <Table
                deleteYesCallback={() => {}}
                columnDefs={AdminSituationUpdateIndexTableHeader()}
                data={adminResponsesList}/>
        </div>
    );
}

export default AdminSituationUpdateList;
