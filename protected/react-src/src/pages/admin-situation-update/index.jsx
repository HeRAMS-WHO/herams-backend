import { __ } from '../../utils/translationsUtility'
import useAdminSituationUpdate from '../../hooks/AdminSituationUpdate/useAdminSituationUpdate'
import AdminSituationUpdateIndexTableHeader from './index/AdminSituationUpdateIndexTableHeader'
import Grid from '@mui/material/Grid'
import Typography from '@mui/material/Typography'
import Button from '@mui/material/Button'
import AddCircleIcon from '@mui/icons-material/AddCircle'
import Item from '@mui/material/Grid'
import MuiTable from '../../components/common/table/MuiTable'
import React from 'react'
import replaceVariablesAsText from '../../utils/replaceVariablesAsText'

const AdminSituationUpdateList = () => {
  const { adminResponsesList } = useAdminSituationUpdate()
  return (
    <Grid container justifyContent="flex-start">
      <Grid item xs={12} sm={12} style={{
        display: 'flex',
        justifyContent: 'flex-end',
      }}>
        <Button
          href={replaceVariablesAsText(`/admin/project/:projectId/workspace/:workspaceId/HSDU/:hsduId/admin-update/create`)}
          startIcon={<AddCircleIcon/>} variant="contained" permissions={['HSDU_ADMIN_CREATE']}>
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
  )
}

export default AdminSituationUpdateList
