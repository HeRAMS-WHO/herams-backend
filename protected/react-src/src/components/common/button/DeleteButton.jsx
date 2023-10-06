import {DeleteIcon} from "../icon/IconsSet"
import './DeleteButton.css'
import {deleteModal} from "../../../utils/modal";

const DeleteButton = ({ title, html, confirmButtonText, cancelButtonText, yesCallback, noCallback }) => {
    const showModal = () => {
        deleteModal({title, html, confirmButtonText, cancelButtonText, yesCallback, noCallback})
    }
    return(
        <>
            <button className='delete-button' onClick={showModal}>
                <DeleteIcon />
            </button>
        </>
    )
}
export default DeleteButton;