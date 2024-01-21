import {__} from "../../utils/translationsUtility";
import useHSDUList from "../../hooks/HSDU/useHSDUList";
import HSDUIndexTableHeader from "./index/HSDUIndexTableHeader";
import Grid from "@mui/material/Grid";
import Typography from "@mui/material/Typography";
import Button from "@mui/material/Button";
import AddCircleIcon from "@mui/icons-material/AddCircle";
import Item from "@mui/material/Grid";
import MuiTable from "../../components/common/table/MuiTable";
import React from "react";
import replaceVariablesAsText from "../../utils/replaceVariablesAsText";

const HSDUList = () => {
    const { HSDUList, isLoading } = useHSDUList(); // Destructure isLoading

    console.log('HSDUList',HSDUList)
    console.log('isLoading',isLoading)

    if (isLoading) {
        return <div>Loading...</div>; // Render a loading indicator or similar while data is being fetched
    }
    return HSDUList && (
        <Grid container>
            <Grid item xs={9}>
                <Typography variant="h5" gutterBottom>
                </Typography>
            </Grid>
            <Grid item xs={3}>
                <Button startIcon={<AddCircleIcon />} variant="contained"
                        href={replaceVariablesAsText( `/admin/project/:projectId/workspace/:workspaceId/HSDU/create`)} permissions={['HSDU_CREATE']}>
                    {__('Register HSDU')}
                </Button>
            </Grid>
            <Grid item xs={12}>
                <Item>
                    <MuiTable
                        columnDefs={HSDUIndexTableHeader()}
                        data={HSDUList}/>
                </Item>
            </Grid>
        </Grid>
    );
}

export default HSDUList;
