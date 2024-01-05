import {__} from "../../utils/translationsUtility";
import useUserList from "../../hooks/User/useUserList";
import UserIndexTableHeader from "./index/UserIndexTableHeader";
import MuiTable from "../../components/common/table/MuiTable";
import Grid from "@mui/material/Grid";
import Item from "@mui/material/Grid";
import Typography from '@mui/material/Typography';
import React from "react";

const UserIndex = () => {
    const { userList } = useUserList()
    return (
        <Grid>
            <Item>
                <Typography variant="h5" gutterBottom>
                    {__('User list')}
                </Typography>
            </Item>
            <Item>
                <MuiTable
                    columnDefs={UserIndexTableHeader()}
                    data={userList}/>
            </Item>
        </Grid>
    );
}

export default UserIndex;
