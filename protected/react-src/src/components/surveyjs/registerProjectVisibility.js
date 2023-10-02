import React from 'react';
import ReactDOM from 'react-dom';
import ProjectVisibilityComponent from './ProjectVisibilityQuestion';
import { Survey } from 'survey-react';
import { CustomWidgetCollection } from 'survey-creator';


export function registerProjectVisibility() {
    CustomWidgetCollection.Instance.add({
        iconName: 'icon-visible',
        name: 'projectvisibility',
        title: 'Project visibility picker',
        category: 'HeRAMS',
        questionJSON: {
            type: 'dropdown',
            name: 'projectvisibility',
            title: 'Project visibility',
            allowClear: false,
            required: true,
            choicesByUrl: {
                url: `/api-proxy/core/configuration/visibilities?_lang=${document.documentElement.lang}`
            }
        },
        onInit() {
            // debugger or any initialization code
        }
    });
}
