import React, { useEffect, useMemo, useState, Suspense } from "react";
import ErrorBoundary from "./components/ErrorBoundary";
import { Link, useLocation } from "react-router-dom";
import reactRoutes from './config/react-routes.json';
import flattenJSONRoutes from "./utils/flattenJSONRoutes";
//const Component = React.lazy(() => import('../src/components/RolePage/RoleList'));
const routes = flattenJSONRoutes(reactRoutes);


const App = () => {
    const [route, setRoute] = useState(null);
    const location = useLocation();

    useEffect(() => {
        //How to get the current namespace?
        console.log(__dirname, "hola")
        for (const route of routes) {
            if (route.URL === location.pathname) {
                const componentRoute = `./components/${route.component}`;
                
        //        setRoute(componentRoute);
            }
        }
    }, []);
    return (
        <div>
            hola
        </div>
);
};

export default App;
