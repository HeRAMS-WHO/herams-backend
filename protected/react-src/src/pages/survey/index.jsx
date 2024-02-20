import useSurveyList from '../../hooks/Survey/useSurveyList'
import SurveyIndexTableHeader from './index/SurveyIndexTableHeader'
import Grid from '@mui/material/Grid'
import Typography from '@mui/material/Typography'
import Button from '@mui/material/Button'
import AddCircleIcon from '@mui/icons-material/AddCircle'
import Item from '@mui/material/Grid'
import MuiTable from '../../components/common/table/MuiTable'
import React from 'react'
import replaceVariablesAsText from '../../utils/replaceVariablesAsText'

const SurveyList = () => {
  const { surveys, refreshSurveys } = useSurveyList()
  return (
    <Grid container justifyContent="flex-start">
      <Grid item xs={12} sm={12} style={{
        display: 'flex',
        justifyContent: 'flex-end',
      }}>
        <Button startIcon={<AddCircleIcon/>} variant="contained"
                href={replaceVariablesAsText(`/admin/survey/create`)} permissions={['CREATE_SURVEY']}>
          {__('Create Survey')}
        </Button>
      </Grid>
      <Grid item xs={12}>
        <Item>
          <MuiTable
            columnDefs={SurveyIndexTableHeader({refreshTable:refreshSurveys})}
            data={surveys}/>
        </Item>
      </Grid>
    </Grid>
  )
}

export default SurveyList
