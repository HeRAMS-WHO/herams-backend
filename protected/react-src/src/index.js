import React from 'react';
import { createRoot } from 'react-dom/client';
import './index.css';
import DemoComponent from './components/DemoComponent';
import Profile from './components/Profile';
import SurveyCreatorWidget from "./components/SurveyCreatorWidget";
import reportWebVitals from "./reportWebVitals";

const componentsMap = {
    'DemoComponent': DemoComponent,
    'Profile': Profile,
    'SurveyCreatorWidget': SurveyCreatorWidget
};

for (const [componentName, Component] of Object.entries(componentsMap)) {
    const root = document.getElementById(componentName);

    // Check if the root exists on the page
    if (root) {
        // Spread the dataset for props
        const props = {...root.dataset};
        createRoot(root).render(<Component {...props} />);
    }
}

// If you want to start measuring performance in your app, pass a function
// to log results (for example: reportWebVitals(console.log))
// or send to an analytics endpoint. Learn more: https://bit.ly/CRA-vitals
reportWebVitals();
