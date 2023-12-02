import reloadInfo from "../utils/reloadInfo"
import params from "../states/params";
import info from "../states/info";

import { languageSelected } from "../states/languageSelected";
const useReloadInfo = () => {
    reloadInfo({info, params})
}

export default useReloadInfo