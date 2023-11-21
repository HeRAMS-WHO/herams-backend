import React from 'react';
import {createRoot} from 'react-dom/client';

import './index.css';
// import 'bootstrap/dist/css/bootstrap-grid.min.css';
import './css/react-tags.css';
import DemoComponent from './components/DemoComponent';
import Profile from './components/ProfilePage/Profile';
import RolesList from './components/RolesPages/RolesList.jsx';
import RolesEdit from "./components/RolesPages/RoleEdit";
import ProjectUserRoles from "./components/UserRoles/ProjectUserRoles";
import SurveyCreatorWidget from "./components/SurveyJs/SurveyCreatorWidget";
import UserIndex from "./components/Users/UserIndex";
import reportWebVitals from "./reportWebVitals";
import WorkspaceUserRoles from "./components/UserRoles/WorkspaceUserRoles";
import GlobalUserRoles from "./components/UserRoles/GlobalUserRoles";
import SurveyWidget from "./components/SurveyJs/SurveyWidget";
import CreateFacility from "./components/FacilityPages/CreateFacility";
import UpdateSituation from "./components/FacilityPages/UpdateSituation";
import CreateAdminSituation from "./components/FacilityPages/AdminSituation/CreateAdminSituation";
import ViewAdminSituation from "./components/FacilityPages/AdminSituation/ViewAdminSituation";
import EditAdminSituation from "./components/FacilityPages/AdminSituation/EditAdminSituation";
import CreateProject from "./components/ProjectPages/CreateProject";
import CreateWorkspace from "./components/Workspace/CreateWorkspace";
import ViewSituation from "./components/FacilityPages/ViewSituation";
import EditSituation from "./components/FacilityPages/EditSituation";
import ViewFacilitySurvey from "./components/FacilityPages/ViewFacilitySurvey";

const componentsMap = {
    'DemoComponent': DemoComponent,
    'Profile': Profile,
    'RolesList': RolesList,
    'RoleEdit': RolesEdit,
    'SurveyCreatorWidget': SurveyCreatorWidget,
    'ProjectUserRoles': ProjectUserRoles,
    'WorkspaceUserRoles': WorkspaceUserRoles,
    'GlobalUserRoles': GlobalUserRoles,
    'UserIndex': UserIndex,
    'SurveyWidget': SurveyWidget,
    'CreateFacility': CreateFacility,
    'UpdateSituation': UpdateSituation,
    'CreateAdminSituation': CreateAdminSituation,
    'ViewAdminSituation': ViewAdminSituation,
    'EditAdminSituation': EditAdminSituation,
    'CreateProject': CreateProject,
    'CreateWorkspace': CreateWorkspace,
    'ViewSituation': ViewSituation,
    'EditSituation': EditSituation,
    'ViewFacilitySurvey': ViewFacilitySurvey,
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
