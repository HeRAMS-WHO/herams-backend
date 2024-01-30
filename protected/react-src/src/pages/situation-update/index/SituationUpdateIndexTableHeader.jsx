import { __ } from '../../../utils/translationsUtility'
import dayjs from 'dayjs'
import { GridActionsCellItem } from '@mui/x-data-grid'
import EditIcon from '@mui/icons-material/Edit'
import VisibilityIcon from '@mui/icons-material/Visibility'
import DeleteIcon from '@mui/icons-material/Delete'
import replaceVariablesAsText from '../../../utils/replaceVariablesAsText'

const SituationUpdateIndexTableHeader = () => {
  return [
    {
      headerName: __('Id'),
      field: 'id',
      type: 'number',
      width: 30,
    },
    {
      headerName: __('Date of update'),
      field: 'date_of_update',
      type: 'date',
      flex: 1,
      valueFormatter: (params) => params.value && dayjs(params.value).format('YYYY-MM-DD'),
    },
    {
      headerName: __('Building condition'),
      field: 'P2_COND_BUILDING',
      flex: 1,
    },
    {
      headerName: __('Functionality'),
      field: 'P2_FUNCIONALITY',
      flex: 1,
    },
    {
      headerName: __('Accessibility'),
      field: 'P2_ACCESSIBILITY',
      flex: 1,
    },
    {
      headerName: __('Status'),
      field: 'status',
      flex: 1,
    },
    {
      headerName: __('Last modified on'),
      field: 'last_modified_date',
      type: 'date',
      flex: 1,
      valueFormatter: (params) => params.value && dayjs(params.value).format('YYYY-MM-DD HH:mm'),
    },
    {
      headerName: __('Last modified by'),
      field: 'last_modified_by',
      flex: 1,
    },
    {
      headerName: __('Actions'),
      field: 'actions',
      type: 'actions',
      flex: 1,
      getActions: (params) => [
        <GridActionsCellItem icon={<EditIcon/>} onClick={() => {
          window.location.href = replaceVariablesAsText(`/admin/project/:projectId/workspace/:workspaceId/HSDU/:hsduId/situation-update/${params.id}/edit`)
        }} label={__('Impersonate')}/>,
        <GridActionsCellItem icon={<VisibilityIcon/>} onClick={() => {
          window.location.href = replaceVariablesAsText(`/admin/project/:projectId/workspace/:workspaceId/HSDU/:hsduId/situation-update/${params.id}`)
        }} label={__('View')}/>,
        <GridActionsCellItem icon={<DeleteIcon/>} onClick={() => {
          window.location.href = replaceVariablesAsText(`/admin/project/:projectId/workspace/:workspaceId/HSDU/:hsduId/settings`)
        }} label={__('Delete')}/>,
      ],
    },
  ]
}

export default SituationUpdateIndexTableHeader
