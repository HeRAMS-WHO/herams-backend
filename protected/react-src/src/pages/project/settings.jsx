import React from "react";
import SurveyFormWidget from "../../components/SurveyJs/SurveyFormWidget";

import { Box, Button, Typography, Dialog, DialogActions, DialogContent, DialogContentText, DialogTitle } from '@mui/material';
import useProjectDeleteWorkspaces from './../../hooks/Project/useProjectDeleteWorkspaces';
import useProjectDelete from './../../hooks/Project/useProjectDelete';
import {__} from "../../utils/translationsUtility";
import replaceVariablesAsText from "../../utils/replaceVariablesAsText";


const ConfirmDialog = ({ open, onClose, onConfirm, title, children }) => (
    <Dialog open={open} onClose={onClose}>
        <DialogTitle>{title}</DialogTitle>
        <DialogContent>
            <DialogContentText>{children}</DialogContentText>
        </DialogContent>
        <DialogActions>
            <Button onClick={onClose} color="primary">
                Cancel
            </Button>
            <Button onClick={onConfirm} color="secondary" variant="contained">
                Confirm
            </Button>
        </DialogActions>
    </Dialog>
);

const ProjectSettings = () => {
    const { projectId } = params.value
    const [openDeleteDialog, setOpenDeleteDialog] = React.useState(false);
    const [openEmptyDialog, setOpenEmptyDialog] = React.useState(false);
    const { deleteWorkspaces, isDeletingWorkspaces } = useProjectDeleteWorkspaces();
    const { projectDelete, isDeletingProject } = useProjectDelete();

    const handleDeleteProject = async () => {
        await projectDelete(projectId);
        if(!isDeletingProject){
            useNavigate()(replaceVariablesAsText('/admin/project'));
        }
        // Handle project deletion here

        setOpenDeleteDialog(false);
    };

    const handleDeleteProjectWorkspaces = async () => {
        await deleteWorkspaces(projectId);
        setOpenEmptyDialog(false);
    };

    return (
        <>
            <Box marginY={2}>
                <Typography variant="h6">{__("Delete project")}</Typography>
                <Typography>{__("This will permanently delete the project and all its workspaces. This action cannot be undone.")}</Typography>
                <Button variant="contained" color="secondary" onClick={() => setOpenDeleteDialog(true)}>
                    {__("Delete")}
                </Button>
            </Box>
            <Box marginY={2}>
                <Typography variant="h6">{__("Empty project")}</Typography>
                <Typography>{__("This will permanently delete all workspaces in the project. This action cannot be undone.")}</Typography>
                <Button variant="contained" color="secondary" onClick={() => setOpenEmptyDialog(true)}>
                    {__("Delete all workspaces")}
                </Button>
            </Box>
            <ConfirmDialog
                open={openDeleteDialog}
                onClose={() => setOpenDeleteDialog(false)}
                onConfirm={handleDeleteProject}
                title={__("Are you ABSOLUTELY SURE you wish to delete this project?")}
            >
                {isDeletingProject ? __("Deleting project") : __("This action cannot be undone.")}
            </ConfirmDialog>
            <ConfirmDialog
                open={openEmptyDialog}
                onClose={() => setOpenEmptyDialog(false)}
                onConfirm={handleDeleteProjectWorkspaces}
                title={__("Are you ABSOLUTELY SURE you wish to delete all workspaces?")}
            >
                {isDeletingWorkspaces ? __("Deleting workspaces...") : __("This action cannot be undone.")}
            </ConfirmDialog>
            <SurveyFormWidget url={`${window.location.origin}/project/create`} />
        </>
    );
};

export default ProjectSettings;
