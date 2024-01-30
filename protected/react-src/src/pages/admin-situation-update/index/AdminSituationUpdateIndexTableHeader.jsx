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
      headerClassName: 'material_table_header_style',
      headerName: __('Id'), // Added headerName
      field: 'id',
      type: 'number',
      width: 30,
    },
    {
      headerClassName: 'material_table_header_style',
      headerName: __('Date of update'),
      field: 'date_of_update',
      type: 'date',
      flex: 1,
      valueFormatter: (params) => params.value && dayjs(params.value).format('YYYY-MM-DD'),
    },
    {
      headerClassName: 'material_table_header_style',
      headerName: __('HSDU Code'),
      field: 'HSDU_CODE',
      flex: 1,
    },
    {
      headerClassName: 'material_table_header_style',
      headerName: __('HSDU Name'),
      field: 'name',
      flex: 1,
    },
    {
      headerClassName: 'material_table_header_style',
      headerName: __('Tier'),
      field: 'HSDU_TYPE_tier',
      flex: 1,
    },
    {
      headerClassName: 'material_table_header_style',
      headerName: __('HSDU Type'),
      field: 'HSDU_TYPE',
      flex: 1,
    },
    {
      headerClassName: 'material_table_header_style',
      headerName: __('Status'),
      field: 'status',
      flex: 1,
    },
    {
      headerClassName: 'material_table_header_style',
      headerName: __('Last modified on'),
      field: 'last_modified_date',
      type: 'date',
      flex: 1,
      valueFormatter: (params) => params.value && dayjs(params.value).format('YYYY-MM-DD HH:MM'),
    },
    {
      headerClassName: 'material_table_header_style',
      headerName: __('Last modified by'),
      field: 'last_modified_by',
      flex: 1,
    },
    {
      headerClassName: 'material_table_header_style',
      headerName: __('Actions'),
      field: 'actions',
      type: 'actions',
      flex: 1,
      getActions: (params) => [
        <GridActionsCellItem icon={<EditIcon/>} onClick={() => {
          return window.location.href = replaceVariablesAsText(`/admin/project/:projectId/workspace/:workspaceId/HSDU/:hsduId/admin-update/${params.id}/edit`)
        }} label={__('Edit')}/>,
        <GridActionsCellItem icon={<VisibilityIcon/>} onClick={() => {
          return window.location.href = replaceVariablesAsText(`/admin/project/:projectId/workspace/:workspaceId/HSDU/:hsduId/admin-update/${params.id}`)
        }} label={__('View')}/>,
        <GridActionsCellItem icon={<DeleteIcon/>} onClick={() => {
          return window.location.href = replaceVariablesAsText(`/admin/project/:projectId/workspace/:workspaceId/HSDU/:hsduId/settings`)
        }} label={__('Delete')}/>,
      ],
    },
  ]
}

export default SituationUpdateIndexTableHeader
