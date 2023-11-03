import React, { Suspense, lazy } from 'react';
import { createRoot } from 'react-dom/client';

import './index.css';
// import 'bootstrap/dist/css/bootstrap-grid.min.css';
import './index.css'

import reportWebVitals from "./reportWebVitals";

// import Profile from './components/ProfilePage/Profile';
// import RolesList from './components/RolesPages/RolesList.jsx';
// import RolesEdit from "./components/RolesPages/RoleEdit";
// // import SurveyCreatorWidget from "./components/SurveyJs/SurveyCreatorWidget";
import SurveyWidget from "./components/SurveyJs/SurveyWidget";



const componentsMap = {
    'Profile': lazy(() => import('./components/ProfilePage/Profile')),
    'RolesList': lazy(() => import('./components/RolesPages/RolesList')),
    'RolesEdit': lazy(() => import('./components/RolesPages/RoleEdit')),
    'SurveyCreatorWidget': lazy(() => import('./components/SurveyJs/SurveyCreatorWidget')),
    'SurveyWidget': lazy(() => import('./components/SurveyJs/SurveyWidget')),
};

// Function to render a React component into a given element
function renderLazyComponent(componentName, elementId) {
    const root = document.getElementById(elementId);

    if (!root) {
        return; // Element not found, skip rendering
    }

    const props = { ...root.dataset };
    const reactRoot = createRoot(root);

    try {
        const LazyComponent = componentsMap[componentName];

        reactRoot.render(
            <Suspense fallback={<div></div>}>
                <LazyComponent {...props} />
            </Suspense>

        );
    } catch (error) {
        console.error(`Error loading or rendering component: ${componentName}`, error);
    }
}

for (const componentName of Object.keys(componentsMap)) {
    renderLazyComponent(componentName, componentName);
}

// If you want to start measuring performance in your app, pass a function
// to log results (for example: reportWebVitals(console.log))
// or send to an analytics endpoint. Learn more: https://bit.ly/CRA-vitals
reportWebVitals();
