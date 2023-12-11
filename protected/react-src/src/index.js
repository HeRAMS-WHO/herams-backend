import React from 'react';
import {createRoot} from "react-dom/client";

import App from './App';
import Profile from './components/ProfilePage/Profile';
import RolesList from './pages/role/index';
import RolesEdit from "./pages/role/update";
import ProjectUserRoles from "./pages/project/user";
import SurveyCreatorWidget from "./components/SurveyJs/SurveyCreatorWidget";
import UserIndex from "./pages/user/index";
import WorkspaceUserRoles from "./pages/workspace/user";
import GlobalUserRoles from "./pages/user/view";
import SurveyWidget from "./components/SurveyJs/SurveyWidget";
import CreateFacility from "./components/FacilityPages/CreateFacility";
import UpdateSituation from "./components/FacilityPages/UpdateSituation";
import CreateAdminSituation from "./components/FacilityPages/AdminSituation/CreateAdminSituation";
import ViewAdminSituation from "./components/FacilityPages/AdminSituation/ViewAdminSituation";
import EditAdminSituation from "./components/FacilityPages/AdminSituation/EditAdminSituation";
import CreateProject from "./pages/project/create";
import CreateWorkspace from "./components/Workspace/CreateWorkspace";
import ViewSituation from "./components/FacilityPages/ViewSituation";
import EditSituation from "./components/FacilityPages/EditSituation";
import ViewFacilitySurvey from "./components/FacilityPages/ViewFacilitySurvey";
import CreateSituationUpdate from './pages/situation-update/create';
import UpdateSituationUpdate from './pages/situation-update/update';
import ViewSituationUpdate from './pages/situation-update/view';
import reportWebVitals from "./reportWebVitals";
import './App.css'
import './index.css';
import './css/react-tags.css';


const root = document.getElementById('root');
const reactRoot = createRoot(root);
reactRoot.render(<>
    <App />
</>);

// If you want to start measuring performance in your app, pass a function
// to log results (for example: reportWebVitals(console.log))
// or send to an analytics endpoint. Learn more: https://bit.ly/CRA-vitals
reportWebVitals();
