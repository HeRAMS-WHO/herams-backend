import useWorkspacesList from '../../hooks/Workspace/useWorkspacesList'
import WorkspaceIndexTableHeader from './index/WorkspaceIndexTableHeader'
import Grid from '@mui/material/Grid'
import Typography from '@mui/material/Typography'
import Button from '@mui/material/Button'
import AddCircleIcon from '@mui/icons-material/AddCircle'
import Item from '@mui/material/Grid'
import MuiTable from '../../components/common/table/MuiTable'

const WorkspacesList = () => {
  const {
    workspacesList,
    isLoading,
  } = useWorkspacesList() // Destructure isLoading
  if (isLoading) {
    return <div>Loading...</div> // Render a loading indicator or similar while data is being fetched
  }
  return (
    <Grid container justifyContent="flex-start">
      <Grid item xs={12} sm={12} style={{
        display: 'flex',
        justifyContent: 'flex-end',
      }}>
        <Button startIcon={<AddCircleIcon/>} variant="contained"
                href={replaceVariablesAsText('/admin/project/:projectId/workspace/create')}
                permissions={['WORKSPACE_CREATE']}>
          {__('Create Workspace')}
        </Button>
      </Grid>
      <Grid item xs={12}>
        <Item>
          <MuiTable
            columnDefs={WorkspaceIndexTableHeader()}
            data={workspacesList}/>
        </Item>
      </Grid>
    </Grid>
  )
}

export default WorkspacesList
