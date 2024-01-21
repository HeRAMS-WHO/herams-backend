import { __ } from '../../../utils/translationsUtility'
import dayjs from 'dayjs'
import { GridActionsCellItem } from '@mui/x-data-grid'
import DeleteIcon from '@mui/icons-material/Delete'

const WorkspaceUserRolesTableHeader = ({ deleteYesCallback }) => [
  {
    headerName: __('Name'),
    renderCell: (data) => data.row.userInfo?.name ?? '',
    field: 'userInfo.name',
    flex: 1,
  },
  {
    headerName: __('Email'),
    renderCell: (data) => data.row.userInfo?.email ?? '',
    field: 'userInfo.email',
    flex: 1,
  },
  {
    headerName: __('Role name'),
    renderCell: (data) => data.row.roleInfo?.name ?? '',
    field: 'roleInfo.name',
    flex: 1,
  },
  {
    headerName: __('Added on'),
    field: 'created_date',
    type: 'date',
    flex: 1,
    valueFormatter: (params) => params.value && dayjs(params.value).format('YYYY-MM-DD HH:mm'),
  },
  {
    headerName: __('Created By'),
    renderCell: (data) => data.row.createdByInfo?.name ?? '',
    field: 'createdByInfo.name',
    flex: 1,
  },
  {
    headerName: __('Actions'),
    field: 'actions',
    type: 'actions',
    flex: 1,
    getActions: (params) => [
      <GridActionsCellItem icon={<DeleteIcon/>} onClick={() => deleteYesCallback(params.id)}
                           label={__('Delete User')}/>,
    ],
  },
]

export default WorkspaceUserRolesTableHeader
