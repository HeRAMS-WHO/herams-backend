import {useEffect, useState} from "react";
import {fetchUsers} from "../../services/apiProxyService";

const useUserList = () => {
    const [userList, setUserList] = useState([])
    useEffect(() => {
        fetchUsers().then((response) => {
            setUserList(response)
        })
    }, [])
    return {
        userList
    }
}

export default useUserList