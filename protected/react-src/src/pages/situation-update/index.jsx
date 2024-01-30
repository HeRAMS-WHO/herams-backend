import { __ } from '../../utils/translationsUtility'
import useResponseList from '../../hooks/SituationUpdate/useSituationUpdate'
import SituationUpdateIndexTableHeader from './index/SituationUpdateIndexTableHeader'
import Grid from '@mui/material/Grid'
import Typography from '@mui/material/Typography'
import Button from '@mui/material/Button'
import AddCircleIcon from '@mui/icons-material/AddCircle'
import Item from '@mui/material/Grid'
import MuiTable from '../../components/common/table/MuiTable'
import React from 'react'
import replaceVariablesAsText from '../../utils/replaceVariablesAsText'

const SituationUpdateList = () => {
  const { responsesList } = useResponseList()
  return (
    <Grid container justifyContent="flex-start">
      <Grid item xs={12} sm={12} style={{
        display: 'flex',
        justifyContent: 'flex-end',
      }}>
        <Button startIcon={<AddCircleIcon/>} variant="contained"
                href={replaceVariablesAsText(`/admin/project/:projectId/workspace/:workspaceId/HSDU/:hsduId/situation-update/create`)}
                permissions={['WORKSPACE_CREATE']}>
          {__('Update Situation')}
        </Button>
      </Grid>
      <Grid item xs={12}>
        <Item>
          <MuiTable
            columnDefs={SituationUpdateIndexTableHeader()}
            data={responsesList}/>
        </Item>
      </Grid>
    </Grid>
  )
}

export default SituationUpdateList
