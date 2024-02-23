import dayjs from 'dayjs'
import { GridActionsCellItem } from '@mui/x-data-grid'
import DeleteIcon from '@mui/icons-material/Delete'

const RoleListHeader = ({ deleteRole }) => [
  {
    headerName: __('Id'),
    field: 'id',
    type: 'number',
    width: 80,
  },
  {
    headerName: __('Name'),
    renderCell: (params) => (
      <Link to={`/admin/role/${params.id}/update`}>{params.value}</Link>
    ),
    field: 'name',
    flex: 1,
  },
  {
    headerName: __('Scope'),
    field: 'scope',
    flex: 1,
  },
  {
    headerName: __('Type'),
    field: 'type',
    flex: 1,
  },
  {
    headerName: __('Project'),
    field: 'project_info',
    valueGetter: (data) => {
      const title = data?.value?.i18n?.title
      const projectName = title && title[languageSelected.value] ? title[languageSelected.value] : title?.en
      return projectName
    },
    flex: 1,
  },
  {
    headerName: __('Created date'),
    field: 'created_date',
    type: 'date',
    flex: 1,
    valueFormatter: (params) => params.value && dayjs(params.value).format('YYYY-MM-DD HH:mm'), // Corrected minute format to 'mm'
  },
  {
    headerName: __('Actions'),
    field: 'actions',
    type: 'actions',
    flex: 1,
    getActions: (params) => [
      <GridActionsCellItem icon={<DeleteIcon/>} onClick={() => deleteRole(params.id)}
                           label={__('Delete User\'s Role')}/>,
    ],
  },
]

export default RoleListHeader
