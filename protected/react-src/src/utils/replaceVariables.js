import { specialVariables } from "../states/info"
const replaceVariablesAsText = (value) => {
    let tempTranslation = value
    specialVariables?.value?.keys?.forEach((key) => {
        tempTranslation = tempTranslation?.replaceAll(key, specialVariables.value?.translations?.[key]);
    });
    return tempTranslation
}

export default replaceVariablesAsText