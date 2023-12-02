import languageSelected from "../states/languageSelected"
import { specialVariables } from "../states/info"
import { reloadSpecialVariables } from "../states/info"

const useReloadSpecialVariables = () => {
    const language = languageSelected.value
    reloadSpecialVariables({language, state:specialVariables})
}

export default useReloadSpecialVariables