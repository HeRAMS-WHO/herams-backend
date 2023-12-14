import {__} from "../../utils/translationsUtility";
import useAdminSituationUpdate from "../../hooks/AdminSituationUpdate/useAdminSituationUpdate";
import AdminSituationUpdateIndexTableHeader from "./index/AdminSituationUpdateIndexTableHeader";
import Table from "../../components/common/table/Table";

const AdminSituationUpdateList = () => {
    const { adminResponsesList } = useAdminSituationUpdate()
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
