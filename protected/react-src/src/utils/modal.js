import Swal from 'sweetalert2'
import withReactContent from 'sweetalert2-react-content'
import {__} from "./translationsUtility";

export const Alert = withReactContent(Swal)

export const toastr = ({icon, timer, title, html}) => {
    return Alert.fire({
        icon,
        timer,
        title,
        html
    })
}

export const modal =  async ({icon, title, html, footer, confirmButtonText, cancelButtonText, yesCallback, noCallback, showCancelButton}) => {
   await Swal.fire({
        icon,
        title,
        html,
        confirmButtonText,
        cancelButtonText,
        showCancelButton,
        footer,
    }).then((result) => {
        if (result.value && typeof yesCallback === 'function') {
            yesCallback()
        }
        if (!result.value && typeof noCallback === 'function') {
            noCallback()
        }
    })
}
export const deleteModal = async ({ title, html, confirmButtonText, cancelButtonText, yesCallback, noCallback }) => {
    const deleteText = __('Delete')
    const cancelText = __('Cancel')

    await modal({
        title,
        html,
        confirmButtonText,
        cancelButtonText,
        icon:'info',
        yesCallback,
        noCallback,
        showCancelButton: true
    })

}
export const modalInfo = ({title, html, footer, yesCallback, noCallback}) => {
    return modal({
        icon: 'info',
        title,
        html,
        footer,
        yesCallback,
        noCallback
    })
}