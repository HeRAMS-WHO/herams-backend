import React from 'react';
import {createRoot} from "react-dom/client";

import App from './App';
import Profile from './components/ProfilePage/Profile';
import ProjectList from './pages/project/index';
import SurveyList from './pages/survey/index';
import RolesList from './pages/role/index';
import RolesEdit from "./pages/role/update";
import ProjectUserRoles from "./pages/project/user";
import SurveyCreatorWidget from "./components/SurveyJs/SurveyCreatorWidget";
import UserIndex from "./pages/user/index";
import UserMaterial from "./components/Users/UserMaterial";
import WorkspaceUserRoles from "./pages/workspace/user";
import GlobalUserRoles from "./pages/user/view";
import SurveyWidget from "./components/SurveyJs/SurveyWidget";
import CreateFacility from "./components/FacilityPages/CreateFacility";
import UpdateSituation from "./components/FacilityPages/UpdateSituation";
import CreateAdminSituation from "./components/FacilityPages/AdminSituation/CreateAdminSituation";
import ViewAdminSituation from "./components/FacilityPages/AdminSituation/ViewAdminSituation";
import EditAdminSituation from "./components/FacilityPages/AdminSituation/EditAdminSituation";
import CreateProject from "./pages/project/create";
import ViewSituation from "./components/FacilityPages/ViewSituation";
import EditSituation from "./components/FacilityPages/EditSituation";
import ViewFacilitySurvey from "./components/FacilityPages/ViewFacilitySurvey";
import WorkspaceUpdate from './pages/workspace/update';
import CreateSituationUpdate from './pages/situation-update/create';
import EditSituationUpdate from './pages/situation-update/edit';
import ViewSituationUpdate from './pages/situation-update/view';
import reportWebVitals from "./reportWebVitals";
import UpdateWorkspace from "./components/Workspace/UpdateWorkspace";
import WorkspacesList from "./pages/workspace/index"
import CreateWorkspace from "./pages/workspace/create"
import HSDUList from "./pages/hsdu/index"
import SituationUpdateList from "./pages/situation-update"
import AdminSituationUpdateList from "./pages/admin-situation-update/index"
import CreateAdminSituationUpdate from './pages/admin-situation-update/create';
import EditAdminSituationUpdate from './pages/admin-situation-update/edit';
import ViewAdminSituationUpdate from './pages/admin-situation-update/view';
// import UpdateProject from "./components/ProjectPages/UpdateProject";
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
