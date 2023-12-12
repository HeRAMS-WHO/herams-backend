import {__} from "../../utils/translationsUtility";
import useResponseList from "../../hooks/SituationUpdate/useSituationUpdate";
import SituationUpdateIndexTableHeader from "./index/SituationUpdateIndexTableHeader";
import Table from "../../components/common/table/Table";

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
