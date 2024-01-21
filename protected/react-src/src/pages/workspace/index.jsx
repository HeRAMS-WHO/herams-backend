import {__} from "../../utils/translationsUtility";
import useWorkspacesList from "../../hooks/Workspace/useWorkspacesList";
import WorkspaceIndexTableHeader from "./index/WorkspaceIndexTableHeader";
import Grid from "@mui/material/Grid";
import Typography from "@mui/material/Typography";
import Button from "@mui/material/Button";
import AddCircleIcon from "@mui/icons-material/AddCircle";
import Item from "@mui/material/Grid";
import MuiTable from "../../components/common/table/MuiTable";
import React from "react";
import replaceVariablesAsText from "../../utils/replaceVariablesAsText";

const WorkspacesList = () => {
    const { workspacesList, isLoading } = useWorkspacesList(); // Destructure isLoading
    if (isLoading) {
        return <div>Loading...</div>; // Render a loading indicator or similar while data is being fetched
    }
    return (
        <Grid container>
            <Grid item xs={9}>
                <Typography variant="h5" gutterBottom>
                </Typography>
            </Grid>
            <Grid item xs={3}>
                <Button startIcon={<AddCircleIcon />} variant="contained"
                        href={replaceVariablesAsText('/admin/project/:projectId/workspace/create')} permissions={['WORKSPACE_CREATE']}>
                    {__('Create Workspace')}
                </Button>
            </Grid>
            <Grid item xs={12}>
                <Item>
                    <MuiTable
                        columnDefs={WorkspaceIndexTableHeader()}
                        data={workspacesList}/>
                </Item>
            </Grid>
        </Grid>
    );
}

export default WorkspacesList;
