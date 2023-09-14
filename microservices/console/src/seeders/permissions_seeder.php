<?php
namespace herams\console\seeders;

$permissions = [
    [
        'code' => 'VIEW_USER',
        'name' => 'View users',
        'parent' => 'Admin',
    ],
    [
        'code' => 'CREATE_USER',
        'name' => 'Add users',
        'parent' => 'Admin',
    ],
    [
        'code' => 'DELETE_USER',
        'name' => 'Delete users',
        'parent' => 'Admin',
    ],
    [
        'code' => 'VIEW_ROLE',
        'name' => 'View Roles',
        'parent' => 'Admin',
    ],
    [
        'code' => 'CREATE_STD_ROLE',
        'name' => 'Add Standard Roles',
        'parent' => 'Admin',
    ],
    [
        'code' => 'CREATE_CUS_ROLE',
        'name' => 'Add Custom Roles',
        'parent' => 'Admin',
    ],
    [
        'code' => 'DELETE_ROLE',
        'name' => 'Delete roles',
        'parent' => 'Admin',
    ],
    [
        'code' => 'ASSIGN_GLOBAL_ROLE',
        'name' => 'Assign global roles to user',
        'parent' => 'Admin',
    ],
    [
        'code' => 'IMPERSONATE_USER',
        'name' => 'Impersonate user',
        'parent' => 'Admin',
    ],
    [
        'code' => 'VIEW_MAP_PUB',
        'name' => 'View Map (public projects)',
        'parent' => 'Homepage',
    ],
    [
        'code' => 'VIEW_MAP_PRIV',
        'name' => 'View Map (private projects)',
        'parent' => 'Homepage',
    ],
    [
        'code' => 'REQUEST_ACCESS_PRIVATE',
        'name' => 'Request access to private project from map pop-up',
        'parent' => 'Homepage',
    ],
    [
        'code' => 'VIEW_MAP_HIDD',
        'name' => 'View Map (hidden projects)',
        'parent' => 'Homepage',
    ],
    [
        'code' => 'VIEW_DASH_PUB',
        'name' => 'View Dashboards (public projects)',
        'parent' => 'Homepage',
    ],
    [
        'code' => 'VIEW_DASH_PRIV',
        'name' => 'View Dashboards (private projects)',
        'parent' => 'Homepage',
    ],
    [
        'code' => 'VIEW_DASH_HIDD',
        'name' => 'View Dashboards (hidden projects)',
        'parent' => 'Homepage',
    ],
    [
        'code' => 'VIEW_PROJECT_LIST_PUB',
        'name' => 'View Project list (public projects)',
        'parent' => 'Global',
    ],
    [
        'code' => 'VIEW_PROJECT_LIST_PRIV',
        'name' => 'View Project list (private projects)',
        'parent' => 'Global',
    ],
    [
        'code' => 'VIEW_PROJECT_LIST_HID',
        'name' => 'View Project list (hidden projects)',
        'parent' => 'Global',
    ],
    [
        'code' => 'CREATE_PROJECT',
        'name' => 'Create project',
        'parent' => 'Global',
    ],
    [
        'code' => 'DELETE_PROJECT',
        'name' => 'Delete project',
        'parent' => 'Global',
    ],
    [
        'code' => 'CREATE_SURVEY',
        'name' => 'Create survey',
        'parent' => 'Global',
    ],
    [
        'code' => 'UPDATE_SURVEY',
        'name' => 'Edit survey',
        'parent' => 'Global',
    ],
    [
        'code' => 'DELETE_SURVEY',
        'name' => 'Delete survey',
        'parent' => 'Global',
    ],
    [
        'code' => 'VIEW_WS_LIST_PUB',
        'name' => 'View Workspaces list (public projects)',
        'parent' => 'Project',
    ],
    [
        'code' => 'VIEW_WS_LIST_NOTPUB',
        'name' => 'View Workspaces list (private and hidden projects)',
        'parent' => 'Project',
    ],
    [
        'code' => 'CREATE_WS',
        'name' => 'Create new workspace',
        'parent' => 'Project',
    ],
    [
        'code' => 'VIEW_PROJECT',
        'name' => 'View project settings',
        'parent' => 'Project',
    ],
    [
        'code' => 'UPDATE_PROJECT',
        'name' => 'Edit project settings',
        'parent' => 'Project',
    ],
    [
        'code' => 'VIEW_DASH',
        'name' => 'View dashboard settings',
        'parent' => 'Project',
    ],
    [
        'code' => 'EDIT_DASH',
        'name' => 'Edit dashboard settings',
        'parent' => 'Project',
    ],
    [
        'code' => 'VIEW_PROJECT_USERS',
        'name' => 'View project users',
        'parent' => 'Project',
    ],
    [
        'code' => 'UPDATE_PROJECT_USERS',
        'name' => 'Manage project users',
        'parent' => 'Project',
    ],
    [
        'code' => 'EXPORT_PROJECT',
        'name' => 'Export project data',
        'parent' => 'Project',
    ],
    [
        'code' => 'IMPORT_PROJECT',
        'name' => 'Import project data',
        'parent' => 'Project',
    ],
    [
        'code' => 'VIEW_HF_LIST',
        'name' => 'View HF List',
        'parent' => 'Workspace',
    ],
    [
        'code' => 'VIEW_WS',
        'name' => 'View workspace settings',
        'parent' => 'Workspace',
    ],
    [
        'code' => 'UPDATE_WS',
        'name' => 'Edit workspace settings',
        'parent' => 'Workspace',
    ],
    [
        'code' => 'VIEW_WS_USERS',
        'name' => 'View workspace users',
        'parent' => 'Workspace',
    ],
    [
        'code' => 'UPDATE_WS_USERS',
        'name' => 'Manage workspace users',
        'parent' => 'Workspace',
    ],
    [
        'code' => 'EXPORT_WS',
        'name' => 'Export workspace data',
        'parent' => 'Workspace',
    ],
    [
        'code' => 'DELETE_WS',
        'name' => 'Delete workspace',
        'parent' => 'Workspace',
    ],
    [
        'code' => 'CREATE_HSDU',
        'name' => 'Create new HF',
        'parent' => 'HSDU',
    ],
    [
        'code' => 'VIEW_SIT_RESPONSE_LIST',
        'name' => 'View Situation updates list',
        'parent' => 'HSDU',
    ],
    [
        'code' => 'VIEW_ADM_RESPONSE_LIST',
        'name' => 'View Admin updates list',
        'parent' => 'HSDU',
    ],
    [
        'code' => 'DELETE_HSDU',
        'name' => 'Delete Health Facility',
        'parent' => 'HSDU',
    ],
    [
        'code' => 'VIEW_ADM_RESPONSE',
        'name' => 'View response (admin data)',
        'parent' => 'HSDU Responses',
    ],
    [
        'code' => 'VIEW_SIT_RESPONSE',
        'name' => 'View response (situation update)',
        'parent' => 'HSDU Responses',
    ],
    [
        'code' => 'VIEW_ADM_DRAFT',
        'name' => 'View drafts (admin data)',
        'parent' => 'HSDU Responses',
    ],
    [
        'code' => 'VIEW_SIT_DRAFT',
        'name' => 'View drafts (situation update)',
        'parent' => 'HSDU Responses',
    ],
    [
        'code' => 'CREATE_ADM_RESPONSE',
        'name' => 'Create drafts and responses (admin data)',
        'parent' => 'HSDU Responses',
    ],
    [
        'code' => 'CREATE_SIT_RESPONSE',
        'name' => 'Create drafts and responses (situation update)',
        'parent' => 'HSDU Responses',
    ],
    [
        'code' => 'UPDATE_ANY_DRAFT',
        'name' => 'Edit any drafts (from any user)',
        'parent' => 'HSDU Responses',
    ],
    [
        'code' => 'UPDATE_ANY_RESPONSE',
        'name' => 'Edit validated responses',
        'parent' => 'HSDU Responses',
    ],
    [
        'code' => 'DELETE_ANY_RESPONSE',
        'name' => 'Delete responses',
        'parent' => 'HSDU Responses',
    ],
];

