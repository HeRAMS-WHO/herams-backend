import FormButtons from "./form/FormButtons";
import {__} from "../../utils/translationsUtility";
import {AddIcon} from "./icon/IconsSet";

const ActionOnHeaderLists = ({label, url}) => {
    return (
        <div className="d-flex gap-1 mt-4 place-end">
            <FormButtons
                buttons={[
                    {
                        label: __(label),
                        class: "btn btn-default w200px",
                        onClick: () => {
                            window.location.href = url;
                        },
                        icon: <AddIcon/>
                    }
                ]}
            />
        </div>
    )
}

export default ActionOnHeaderLists;