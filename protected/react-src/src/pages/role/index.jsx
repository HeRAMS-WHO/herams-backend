import useRoleList from '../../hooks/Role/useRoleList'
import RoleListHeader from './index/RoleListHeader'
import { fetchDeleteRole } from '../../services/apiProxyService'
import Item from '@mui/material/Grid'
import Typography from '@mui/material/Typography'
import { __ } from '../../utils/translationsUtility'
import MuiTable from '../../components/common/table/MuiTable'
import Grid from '@mui/material/Grid'
import React from 'react'
import AddCircleIcon from '@mui/icons-material/AddCircle'
import Button from '@mui/material/Button'

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
        <Button startIcon={<AddCircleIcon/>} variant="contained"
                href={`/admin/role/create`} permissions={['ROLE_CREATE']}>
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
