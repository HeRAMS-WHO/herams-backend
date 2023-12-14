import {useEffect, useState} from 'react';
import reactRoutes from '../config/react-routes.json';
import flattenJSONRoutes from "../utils/flattenJSONRoutes";
import routesMap  from '../routes-map.json';
import { match } from 'path-to-regexp'
import { fetchLocales } from '../services/apiProxyService';
import locales from '../states/locales';
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

const urlDataRetriever = ({url, routes}) => {
    let data = null; 
    const allRoutes = Object.keys(routes)
    const route = allRoutes.find((route) => {
        const matcher = match(route, { decode: decodeURIComponent })
        const result = matcher(url)
        console.log('result', result);
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
        const {currentPage, genericUrl} = urlDataRetriever({url: location.value, routes});        
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
        fetchLocales().then((response) => {
            locales.value = response.map((locale) => (
                {value:locale.locale.toLowerCase(), label: locale.locale.toUpperCase()}
            ))
        })
    }, [languageSelected.value])
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
