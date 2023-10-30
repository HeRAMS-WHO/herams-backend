import React from 'react';
import {createRoot} from 'react-dom/client';

import './index.css';
// import 'bootstrap/dist/css/bootstrap-grid.min.css';
import './css/react-tags.css';
import DemoComponent from './components/DemoComponent';
import Profile from './components/ProfilePage/Profile';
import RolesList from './components/RolesPages/RolesList.jsx';
import RolesEdit from "./components/RolesPages/RoleEdit";
import UserRoles from "./components/UserRoles/UserRoles";
import SurveyCreatorWidget from "./components/SurveyJs/SurveyCreatorWidget";

import reportWebVitals from "./reportWebVitals";
import WorkspaceUserRoles from "./components/UserRoles/WorkspaceUserRoles";

const componentsMap = {
    'DemoComponent': DemoComponent,
    'Profile': Profile,
    'RolesList': RolesList,
    'RoleEdit': RolesEdit,
    'SurveyCreatorWidget': SurveyCreatorWidget,
    'UserRoles': UserRoles,
    'WorkspaceUserRoles': WorkspaceUserRoles
};

for (const [componentName, Component] of Object.entries(componentsMap)) {
    const root = document.getElementById(componentName);

    // Check if the root exists on the page
    if (root) {
        // Spread the dataset for props
        const props = {...root.dataset};
        const reactRoot = createRoot(root);
        reactRoot.render(<Component {...props} />);
    }
}

// If you want to start measuring performance in your app, pass a function
// to log results (for example: reportWebVitals(console.log))
// or send to an analytics endpoint. Learn more: https://bit.ly/CRA-vitals
reportWebVitals();
