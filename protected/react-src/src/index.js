import React from 'react';
import {createRoot} from "react-dom/client";

import App from './App';
import ProjectList from './pages/project/index';
import ProjectSettings from './pages/project/settings';
import SurveyList from './pages/survey/index';
import CreateSurvey from './pages/survey/create';
import EditSurvey from './pages/survey/edit';
import RolesList from './pages/role/index';
import RolesEdit from "./pages/role/update";
import ProjectUserRoles from "./pages/project/user";
import UserIndex from "./pages/user/index";
import Material from "./pages/material/index";
import WorkspaceUserRoles from "./pages/workspace/user";
import GlobalUserRoles from "./pages/user/view";
import CreateProject from "./pages/project/create";
import ProjectImport from "./pages/project/import";
import WorkspaceUpdate from './pages/workspace/update';
import CreateSituationUpdate from './pages/situation-update/create';
import EditSituationUpdate from './pages/situation-update/edit';
import ViewSituationUpdate from './pages/situation-update/view';
import reportWebVitals from "./reportWebVitals";
import WorkspacesList from "./pages/workspace/index"
import CreateWorkspace from "./pages/workspace/create"
import HSDUList from "./pages/hsdu/index"
import HSDUSettings from "./pages/hsdu/delete"
import CreateHSDU from "./pages/hsdu/create"
import SituationUpdateList from "./pages/situation-update"
import AdminSituationUpdateList from "./pages/admin-situation-update/index"
import CreateAdminSituationUpdate from './pages/admin-situation-update/create';
import EditAdminSituationUpdate from './pages/admin-situation-update/edit';
import ViewAdminSituationUpdate from './pages/admin-situation-update/view';
import AuthLogin from './pages/auth/login';
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
