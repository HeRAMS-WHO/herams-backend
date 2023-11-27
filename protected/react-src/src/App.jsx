import React, { useEffect, useMemo, useState, Suspense } from "react";
import reactRoutes from './config/react-routes.json';
import flattenJSONRoutes from "./utils/flattenJSONRoutes";
import {
    BrowserRouter as Router,
    Switch,
    Route,
    Routes,
    Link,
    useLocation
  } from "react-router-dom";
  const routes = flattenJSONRoutes(reactRoutes);
  const pages = {};
  for (const routeKey in routes) {
      const route = routes[routeKey];
      const { component } = route;
      if (!component) {
          delete routes[routeKey];
          continue;
      }
      pages[routeKey] = React.lazy(() => import(`./components/${component}`));
  }
  
const Page = () => {
    const [Component, setComponent] = useState(null) 
    const location = useLocation();
    useEffect(() => {
        if (pages[location.pathname]) {
            setComponent(pages[location.pathname])

        }
        if (!pages[location.pathname]) {
            window.location.reload();
            return;
        }
        
    }, [location])
    return (<>
        {Component && <Component/>}
    </>)
}
const App = () => {
    return (
        <>
            <Router>
                <Page/>
            </Router>
        </>
    )
};

export default App;
