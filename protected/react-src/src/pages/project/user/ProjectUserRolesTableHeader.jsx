import { __ } from '../../../utils/translationsUtility'
import dayjs from 'dayjs'
import { GridActionsCellItem } from '@mui/x-data-grid'
import { UserIcon } from '../../../components/common/icon/IconsSet'
import DeleteIcon from '@mui/icons-material/Delete'

const ProjectUserRolesTableHeader = ({ deleteYesCallback }) => [
  {
    headerName: __('Name'), // Use headerName for CSV export and table display
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
    headerName: __('Workspace'),
    renderCell: (data) => {
      if (data.row.target === 'project') {
        return '--'
      }
      const primary_language = data.row.projectInfo?.primary_language ?? 'en'
      const { title } = JSON.parse(data.row.workspaceInfo.i18n)
      return title[primary_language]
    },
    field: 'workspaceInfo.title', // Adjusted field for consistency
    flex: 1,
  },
  {
    headerName: __('Added on'),
    field: 'created_date',
    type: 'date',
    flex: 1,
    valueFormatter: (params) => params.value && dayjs(params.value).format('YYYY-MM-DD HH:mm'), // Fixed minute format to lowercase 'mm'
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

export default ProjectUserRolesTableHeader
