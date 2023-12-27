import {__} from "../../utils/translationsUtility";
import useSurveyList from "../../hooks/Survey/useSurveyList";
import SurveyIndexTableHeader from "./index/SurveyIndexTableHeader";
import Table from "../../components/common/table/Table";
import ActionOnHeaderLists from "../../components/common/ActionOnHeaderLists";
import replaceVariablesAsText from "../../utils/replaceVariablesAsText";

const SurveyList = () => {
    const { surveys } = useSurveyList()

    return (
        <div className="container-fluid px-2">
            <div className="row mt-2">
                <div className="col-md-6">
                    <h1 className="mt-3">
                        {__('Surveys')}
                    </h1>
                </div>
                <div className="col-md-6">
                    <ActionOnHeaderLists
                        label={__('Create survey')}
                        permissions={['CREATE_SURVEY']}
                        url={replaceVariablesAsText( `/admin/survey/create`)}/>
                </div>
            </div>
            <Table
                deleteYesCallback={() => {}}
                columnDefs={SurveyIndexTableHeader()}
                data={surveys}/>
        </div>
    );
}

export default SurveyList;
