import {useEffect, useState} from 'react';
import reactRoutes from '../config/react-routes.json';
import flattenJSONRoutes from "../utils/flattenJSONRoutes";
import routesMap  from '../routes-map.json';
import { match } from 'path-to-regexp'
const routes = flattenJSONRoutes(reactRoutes);
const pages = {};
for (const routeKey in routes) {
    const route = routes[routeKey];
    const { page } = route;
    if (!page) {
        continue;
    }
    pages[routeKey] = React.lazy(() => import(`../pages/${page}`));
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
const useApp = () => {
    const [Page, setPage] = useState(null)
    const [layout, setLayout] = useState('AdminLayout')

    useEffect(() => {
        const {currentPage, genericUrl} = urlDateRetriever({url: location.value, routes});        
        const routeData = reactRoutes[genericUrl]
        if (routeData.redirectTo){
            useNavigate()(replaceVariablesAsText(routeData.redirectTo || '/'))    
        }
        else if (routeData === null || !routeData.page){
            window.location.href = replaceVariablesAsText((location.value !== routesMap[location.value]) ?
                routesMap[location.value] || '' :
                location.value || '')
        }
        else if (routeData !== null || routeData.page){
            const component = pages[routeData?.URL]
            routeInfo.value = routeData;
            params.value = currentPage.params;
            setLayout(routeData?.layout || 'AdminLayout')
            setPage(component)
        }
    }, [location.value])
    useEffect(() => {
        reloadInfo({info, params})
    }, [params.value])
    useEffect(() => {
        useReloadSpecialVariables()
    }, [info.value, languageSelected.value])
    return {
        Page,
        reactRoutes,
        layout
    }
}

export default useApp