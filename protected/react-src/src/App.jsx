import React, {useEffect, useState} from "react";
import reactRoutes from './config/react-routes.json';
import routesMap from './routes-map.json';
import flattenJSONRoutes from "./utils/flattenJSONRoutes";
import {BrowserRouter as Router, useLocation, useNavigate} from "react-router-dom";
import Header from "./components/common/Navbar";
import params from "./states/params";
import reloadInfo  from "./utils/reloadInfo"
import routeInfo from "./states/routeInfo";
import info, {reloadSpecialVariables, specialVariables} from "./states/info";
import languageSelected from "./states/languageSelected";
import TabsMenu from "./components/common/TabMenu";
import useReloadSpecialVariables from "./hooks/useReloadSpecialVariables";
import replaceVariablesAsText from "./utils/replaceVariables";
import Breadcrumb from "./components/common/Breadcrumb";
import { match } from 'path-to-regexp'

const routes = flattenJSONRoutes(reactRoutes);
const pages = {};
for (const routeKey in routes) {
    const route = routes[routeKey];
    const { page } = route;
    if (!page) {
        continue;
    }
    pages[routeKey] = React.lazy(() => import(`./pages/${page}`));
}
const urlDateRetriever = ({url, routes}) => {
    let data = null; 
    const allRoutes = Object.keys(routes)
    const route = allRoutes.find((route) => {
        const matcher = match(route, { decode: decodeURIComponent })
        const result = matcher(url)
        if (result){
            data = {currentPage: result, genericUrl: route}
        }
        return result
    })
    return data;
}

const Page = () => {
    const navigator = useNavigate()
    const [Component, setComponent] = useState(null) 
    const locationRouter = useLocation();
    useEffect(() => {
        const location = window.location
        const {currentPage, genericUrl} = urlDateRetriever({url: location.pathname, routes});        
        const routeData = reactRoutes[genericUrl]
        if (routeData.redirectTo){
            navigator(replaceVariablesAsText(routeData.redirectTo))    
        }
        else if (routeData === null || !routeData.page){
            window.location.href = replaceVariablesAsText((location.pathname !== routesMap[location.pathname]) ?
                routesMap[location.pathname] :
                location.pathname)
        }
        else if (routeData !== null || routeData.page){
            const component = pages[routeData?.URL]
            routeInfo.value = routeData;
            params.value = currentPage.params;
            setComponent(component)
        }
    }, [locationRouter])
    useEffect(() => {
        reloadInfo({info, params})
    }, [params.value])
    return (<>
        {Component && <Component/>}
    </>)
}
const MainContent = ({children}) => {
    return (
        <div className="col-md-8 mx-auto bg-white col-12 py-2 px-2">
            {children}
        </div>
    )
}
const BreadcrumbContainer = ({children}) => {
    return (
        <div className="col-md-8 mx-auto col-12 mt-2 mb-2 p-0 px-2">
            {children}
        </div>
    )
}
const TabContainer = ({children}) => {
    return (
        <div className="col-md-8 mx-auto col-12 mt-0 p-0">
            {children}
        </div>
    )
}

const App = () => {
    useEffect(() => {
        useReloadSpecialVariables()
    }, [info.value, languageSelected.value])
    return (
        <>
            <Router>
                <Header />
                <BreadcrumbContainer>
                    <Breadcrumb routes={reactRoutes} />
                </BreadcrumbContainer>
                <TabContainer>
                    <TabsMenu routes={reactRoutes} />
                </TabContainer>
                <MainContent>
                    <Page />
                </MainContent>
            </Router>
        </>
    )
};

export default App;
