import {__} from "../../utils/translationsUtility";
import useUserList from "../../hooks/User/useUserList";
import UserIndexTableHeader from "./UserIndexTableHeader";
import MuiTable from "../common/table/MuiTable";
import Grid from "@mui/material/Grid";
import Item from "@mui/material/Grid";
import React from "react";

const UserIndex = () => {
    const { userList } = useUserList()
    return (
        <Grid className="container-fluid px-2">
            <Item>
                <h2>{__('User list')}</h2>
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
