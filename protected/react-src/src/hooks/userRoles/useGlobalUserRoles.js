import {useEffect, useState} from "react";
import {fetchAUserInformation} from "../../services/apiProxyService";

const useGlobalUserRoles = ({userId}) => {
    const [userInfo, setUserInfo] = useState([])
    useEffect(() => {
        fetchAUserInformation(userId).then((response) => {
            setUserInfo(response);
        })
    }, [userId]);
    return {
        userInfo,
    }
}

export default useGlobalUserRoles