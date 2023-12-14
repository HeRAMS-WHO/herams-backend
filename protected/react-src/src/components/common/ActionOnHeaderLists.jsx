import {__} from "../../utils/translationsUtility";
import {AddIcon} from "./icon/IconsSet";

const ActionOnHeaderLists = ({label, url}) => {
    return (
        <div className="d-flex gap-1 mt-4 place-end">
            <Link to={url} className="btn btn-default w200px">
                <AddIcon/> {__(label)}
            </Link>
        </div>
    )
}

export default ActionOnHeaderLists;