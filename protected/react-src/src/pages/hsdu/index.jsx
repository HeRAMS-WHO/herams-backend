import {__} from "../../utils/translationsUtility";
import useHSDUList from "../../hooks/HSDU/useHSDUList";
import HSDUIndexTableHeader from "./index/HSDUIndexTableHeader";
import Table from "../../components/common/table/Table";

const HSDUList = () => {
    const { HSDUList, isLoading } = useHSDUList(); // Destructure isLoading

    console.log('HSDUList',HSDUList)
    console.log('isLoading',isLoading)

    if (isLoading) {
        return <div>Loading...</div>; // Render a loading indicator or similar while data is being fetched
    }
    return (
        <div className="container-fluid px-2">
            <div className="row mt-2">
                <div className="col-md-12">
                    <h1 className="mt-3">
                        {__('HSDU list')}
                    </h1>
                </div>
            </div>
            <div className="row mt-2">
                <div className="col-md-12">

                </div>
            </div>
            <Table
                deleteYesCallback={() => {}}
                columnDefs={HSDUIndexTableHeader()}
                data={HSDUList}/>
        </div>
    );
}

export default HSDUList;
