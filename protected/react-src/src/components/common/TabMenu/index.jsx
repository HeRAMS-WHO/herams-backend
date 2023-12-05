import { useEffect, useState } from "react"
import { Link } from "react-router-dom"
import routeInfo from "../../../states/routeInfo"
import replaceVariablesAsText from "../../../utils/replaceVariables"
import './index.css'
const Tab = ({page}) => {
    return (
        <>
            <Link
                to={replaceVariablesAsText(page.URL)} 
                className={`tab ${page?.URL === routeInfo.value?.URL ? 'activeTab' : ''}`}>
                {replaceVariablesAsText(page.tabName)}
            </Link>
        </>
    )
}
const TabsMenu = ({routes}) => {
    const [tabs, setTabs] = useState([])
    useEffect(() => {
        const splitURL = routeInfo.value?.URL?.split('/')
        const URLWithoutLast = splitURL?.slice(0, splitURL.length - 1).join('/')
        const urlLevel = splitURL?.length
        const tempTabs = []
        Object.keys(routes).forEach((route) => {
            const splitRoute = route.split('/')
            const routeLevel = splitRoute.length
            const routeWithoutLast = splitRoute.slice(0, splitRoute.length - 1).join('/')
            if (!routes[route].hasTabs){
                return ;
            }
            if (routeWithoutLast == URLWithoutLast) {
                tempTabs.push(routes[route])
            }
        })
        setTabs(tempTabs)
    }, [routeInfo.value, routes])
    return (
        <div className="d-flex bg-aliceblue">
            {
                tabs.filter((page) => page.hasTabs)
                    .map((page) => <Tab key={page.URL} page={page} />)
            }
        </div>
    )
}

export default TabsMenu;