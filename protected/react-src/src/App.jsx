import React, {useEffect, useState} from "react";
import reactRoutes from './config/react-routes.json';
import flattenJSONRoutes from "./utils/flattenJSONRoutes";
import {BrowserRouter as Router, useLocation} from "react-router-dom";
import Header from "./components/common/Navbar";
import params from "./states/params";
import reloadInfo  from "./utils/reloadInfo"
import routeInfo from "./states/routeInfo";
import info, {reloadSpecialVariables, specialVariables} from "./states/info";
import languageSelected from "./states/languageSelected";
import TabsMenu from "./components/common/TabMenu";
import useReloadSpecialVariables from "./hooks/useReloadSpecialVariables";
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
const urlRouteDataFinder = ({url, routes}) => {
    let routeData = null;
    Object.keys(routes).forEach((route) => {
        const regex = new RegExp(route.replace(/\:\w+/g, '\\d+'));
        if (regex.test(url)) {
            routeData = routes[route];
        }
    })
    return routeData;
}
const urlParamsExtractor = ({urlGeneric, currentURL}) => {
    const urlGenericSplit = urlGeneric?.split('/');
    const currentURLSplit = currentURL?.split('/');
    const paramsInUrl = {};
    urlGenericSplit?.forEach((item, index) => {
        if (item.startsWith(':')) {
            const key = item.replace(':', '');
            paramsInUrl[key] = currentURLSplit[index];
        }
    })
    return paramsInUrl;
}

const Page = () => {
    const [Component, setComponent] = useState(null) 
    const location = useLocation();
    useEffect(() => {
        const routeData = urlRouteDataFinder({url: location.pathname, routes});
        const routeParams = urlParamsExtractor({urlGeneric: routeData.URL, currentURL: location.pathname});
        if (routeData == null || !routeData.page) {
            window.location.reload();
            return;
        }
        routeInfo.value = routeData;
        params.value = routeParams;
        setComponent(pages[routeData.URL]);
    }, [location])
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
                <TabContainer>
                    <TabsMenu routes={reactRoutes}/>
                </TabContainer>
                <MainContent>
                    <Page />
                </MainContent>
            </Router>
        </>
    )
};

export default App;
