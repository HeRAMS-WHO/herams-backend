import {__} from "../../utils/translationsUtility";
import useUserList from "../../hooks/User/useUserList";
import UserIndexTableHeader from "./index/UserIndexTableHeader";
import Table from "../../components/common/table/Table";

const UserIndex = () => {
    const { userList } = useUserList()
    return (
        <div className="container-fluid px-2">
            <div className="row mt-2">
                <div className="col-md-12">
                    <h1 className="mt-3">
                        {__('User list')}
                    </h1>
                </div>
            </div>
            <div className="row mt-2">
                <div className="col-md-12">

                </div>
            </div>
            <Table
                deleteYesCallback={() => {}}
                columnDefs={UserIndexTableHeader()}
                data={userList}/>
        </div>
    );
}

export default UserIndex;