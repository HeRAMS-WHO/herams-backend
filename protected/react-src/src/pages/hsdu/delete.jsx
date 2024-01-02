import React from "react";
import { Box, Button, Typography, Dialog, DialogActions, DialogContent, DialogContentText, DialogTitle } from '@mui/material';
import useProjectDelete from './../../hooks/HSDU/useHSDUDelete';
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

const HSDUSettings = () => {
    const { hsduId } = params.value
    const [openDeleteDialog, setOpenDeleteDialog] = React.useState(false);
    const { HSDUDelete, isDeletingHSDU} = useProjectDelete();

    const handleDeleteProject = async () => {
        await HSDUDelete(hsduId);
        if(!isDeletingHSDU){
            useNavigate()(replaceVariablesAsText('/admin/project'));
        }
        // Handle project deletion here

        setOpenDeleteDialog(false);
    };

    return (
        <>
            <Box marginY={2}>
                <Typography variant="h6">{__("Delete HSDU")}</Typography>
                <Typography>{__("This will permanently delete the HSDU. This action cannot be undone.")}</Typography>
                <Button variant="contained" color="secondary" onClick={() => setOpenDeleteDialog(true)}>
                    {__("Delete")}
                </Button>
            </Box>
            <ConfirmDialog
                open={openDeleteDialog}
                onClose={() => setOpenDeleteDialog(false)}
                onConfirm={handleDeleteProject}
                title={__("Are you ABSOLUTELY SURE you wish to delete this HSDU?")}
            >
                {isDeletingHSDU ? __("Deleting HSDU") : __("This action cannot be undone.")}
            </ConfirmDialog>
        </>
    );
};

export default HSDUSettings;
