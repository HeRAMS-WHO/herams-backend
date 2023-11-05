import {DeleteIcon} from "../icon/IconsSet"
import './DeleteButton.css'

const DeleteButton = ({ title, html, confirmButtonText, cancelButtonText, yesCallback, noCallback }) => {
    const showModal = () => {
        yesCallback()
        //deleteModal({title, html, confirmButtonText, cancelButtonText, yesCallback, noCallback})
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