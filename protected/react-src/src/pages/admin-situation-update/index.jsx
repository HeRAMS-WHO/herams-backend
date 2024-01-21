import {__} from "../../utils/translationsUtility";
import useAdminSituationUpdate from "../../hooks/AdminSituationUpdate/useAdminSituationUpdate";
import AdminSituationUpdateIndexTableHeader from "./index/AdminSituationUpdateIndexTableHeader";
import Grid from "@mui/material/Grid";
import Typography from "@mui/material/Typography";
import Button from "@mui/material/Button";
import AddCircleIcon from "@mui/icons-material/AddCircle";
import Item from "@mui/material/Grid";
import MuiTable from "../../components/common/table/MuiTable";
import React from "react";
import replaceVariablesAsText from "../../utils/replaceVariablesAsText";

const AdminSituationUpdateList = () => {
    const { adminResponsesList } = useAdminSituationUpdate()
    return (
        <Grid container>
            <Grid item xs={9}>
                <Typography variant="h5" gutterBottom>
                </Typography>
            </Grid>
            <Grid item xs={3}>
                <Button href={replaceVariablesAsText( `/admin/project/:projectId/workspace/:workspaceId/HSDU/:hsduId/admin-update/create`)}
                        startIcon={<AddCircleIcon />} variant="contained" permissions={['HSDU_ADMIN_CREATE']}>
                    {__('Update HSDU Info')}
                </Button>
            </Grid>
            <Grid item xs={12}>
                <Item>
                    <MuiTable
                        columnDefs={AdminSituationUpdateIndexTableHeader()}
                        data={adminResponsesList}/>
                </Item>
            </Grid>
        </Grid>
    );
}

export default AdminSituationUpdateList;
