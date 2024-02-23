import useRoleList from '../../hooks/Role/useRoleList'
import RoleListHeader from './index/RoleListHeader'
import { fetchDeleteRole } from '../../services/apiProxyService'
import Item from '@mui/material/Grid'
import MuiTable from '../../components/common/table/MuiTable'
import Grid from '@mui/material/Grid'
import React from 'react'
import AddCircleIcon from '@mui/icons-material/AddCircle'
import { Button } from '@mui/material'
const RolesList = () => {
  const {
    rolesList,
    refreshRolesList,
  } = useRoleList()
  const deleteRole = (id) => {
    fetchDeleteRole(id)
      .then(() => {
        refreshRolesList()
      })
  }

  return (
    <Grid container justifyContent="flex-start">
      <Grid item xs={12} sm={12} style={{
        display: 'flex',
        justifyContent: 'flex-end',
      }}>
        <Button 
          startIcon={<AddCircleIcon/>}
          permissions={['ROLE_CREATE']}
          onClick={() => {useNavigate()(`/admin/role/create`)}}
          variant="contained">
            {__('Create Role')}
        </Button>        
      </Grid>
      <Grid item xs={12}>
        <Item>
          <MuiTable
            columnDefs={RoleListHeader({ deleteRole })}
            data={rolesList}/>
        </Item>
      </Grid>
    </Grid>
  )

}

export default RolesList
