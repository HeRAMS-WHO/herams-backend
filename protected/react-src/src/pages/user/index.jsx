import {__} from "../../utils/translationsUtility";
import useUserList from "../../hooks/User/useUserList";
import UserIndexTableHeader from "./index/UserIndexTableHeader";
import MuiTable from "../../components/common/table/MuiTable";
import Grid from "@mui/material/Grid";
import Item from "@mui/material/Grid";
import Typography from '@mui/material/Typography';
import React from "react";
import Button from "@mui/material/Button";
import AddCircleIcon from "@mui/icons-material/AddCircle";

const UserIndex = () => {
    const { userList } = useUserList()
    return (
        <Grid container>
            <Grid item xs={10}>
                <Typography variant="h5" gutterBottom>
                </Typography>
            </Grid>
            <Grid item xs={2}>
                <Button startIcon={<AddCircleIcon />} variant="contained"
                        href={`/admin/user/create`} permissions={['USER_CREATE']}>
                    {__('Create User')}
                </Button>
            </Grid>
            <Grid item xs={12}>
                <Item>
                    <MuiTable
                        columnDefs={UserIndexTableHeader()}
                        data={userList}/>
                </Item>
            </Grid>
        </Grid>
    );
}

export default UserIndex;
