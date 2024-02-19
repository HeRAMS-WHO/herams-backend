import useHSDUList from '../../hooks/HSDU/useHSDUList'
import HSDUIndexTableHeader from './index/HSDUIndexTableHeader'
import Grid from '@mui/material/Grid'
import Button from '@mui/material/Button'
import AddCircleIcon from '@mui/icons-material/AddCircle'
import Item from '@mui/material/Grid'
import MuiTable from '../../components/common/table/MuiTable'
import React from 'react'
import replaceVariablesAsText from '../../utils/replaceVariablesAsText'

const HSDUList = () => {
  const {
    HSDUList,
    isLoading,
  } = useHSDUList() // Destructure isLoading

 

  if (isLoading) {
    return <div>Loading...</div> // Render a loading indicator or similar while data is being fetched
  }
  return HSDUList && (
    <Grid container justifyContent="flex-start">
      <Grid item xs={12} sm={12} style={{
        display: 'flex',
        justifyContent: 'flex-end',
      }}>
        <Button startIcon={<AddCircleIcon/>} variant="contained"
                href={replaceVariablesAsText(`/admin/project/:projectId/workspace/:workspaceId/HSDU/create`)}
                permissions={['HSDU_CREATE']}>
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
  )
}

export default HSDUList
