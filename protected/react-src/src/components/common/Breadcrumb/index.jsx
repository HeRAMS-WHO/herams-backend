import { Link } from "react-router-dom"
import routeInfo from "../../../states/routeInfo"
import reactRoutes from '../../../config/react-routes.json'
import replaceVariablesAsText from "../../../utils/replaceVariables"
const extractRoutesRecursively = (route) => {
    const routes = route.split('/')
    const routesArray = []
    let routeString = ''
    routes.forEach((route) => {
        if (route === '') return;
        routeString += `/${route}`
        routesArray.push(routeString)
    })
    return routesArray;
}
const BreadcrumbLink = ({link, isLast, children}) => {
    if (isLast) return (<> {children}</>)
    return (<>
        <Link to={link} className="text-underlined text-dark hover-text-primary">
            {children}
        </Link> <span className="text-muted">/ </span> 
    </>)
}
const Breadcrumb = ({routes}) => {
    const routesArray = extractRoutesRecursively(routeInfo?.value?.URL || '/')
    return (<>
        {routesArray.map((route, index) => {
            const {URL : url = '', pageTitle, tabName = null} = reactRoutes[route] || {}
            if (!url) return null;
            return (<BreadcrumbLink 
                link={replaceVariablesAsText(url)} 
                isLast={index === routesArray.length - 1}
                key={route}>
                    {replaceVariablesAsText(tabName || pageTitle)}
                </BreadcrumbLink>)
        })}</>)}

export default Breadcrumb