import { __ } from '../../utils/translationsUtility'
import useProjects from '../../hooks/Project/useProjects'
import ProjectIndexTableHeader from './index/ProjectIndexTableHeader'
import Grid from '@mui/material/Grid'
import Typography from '@mui/material/Typography'
import Button from '@mui/material/Button'
import AddCircleIcon from '@mui/icons-material/AddCircle'
import Item from '@mui/material/Grid'
import MuiTable from '../../components/common/table/MuiTable'
import React from 'react'
import replaceVariablesAsText from '../../utils/replaceVariablesAsText'

const ProjectList = () => {
  const { projects } = useProjects()
  return (
    <Grid container justifyContent="flex-start">
      <Grid item xs={12} sm={12} style={{
        display: 'flex',
        justifyContent: 'flex-end',
      }}>
        <Button startIcon={<AddCircleIcon/>} variant="contained"
                href={replaceVariablesAsText(`/admin/project/create`)} permissions={['CREATE_PROJECT']}>
          {__('Create project')}
        </Button>
      </Grid>
      <Grid item xs={12}>
        <Item>
          <MuiTable
            columnDefs={ProjectIndexTableHeader()}
            data={projects}/>
        </Item>
      </Grid>
    </Grid>
  )
}

export default ProjectList
