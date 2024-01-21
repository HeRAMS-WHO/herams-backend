import { __ } from '../../../utils/translationsUtility'
import Link from '@mui/material/Link'
import dayjs from 'dayjs'

const WorkspacesIndexTableHeader = () => [
  {
    headerName: __('Id'),
    field: 'id',
    type: 'number',
    width: 80,
  },
  {
    headerName: __('Title'),
    renderCell: (params) => (
      <Link href={`/admin/project/${params.row.project_id}/workspace/${params.id}/HSDU`}>{params.value}</Link>
    ),
    field: 'name',
    flex: 1,
  },
  {
    headerName: __('Date of update'),
    field: 'date_of_update',
    type: 'date',
    flex: 1,
    valueFormatter: (params) => params.value && dayjs(params.value).format('YYYY-MM-DD HH:mm'),
  },
  {
    headerName: __('Contributors'),
    field: 'contributorCount',
    type: 'number',
    flex: 1,
  },
  {
    headerName: __('HSDUs'),
    field: 'facilityCount',
    type: 'number',
    flex: 1,
  },
  {
    headerName: __('Responses'),
    field: 'responseCount',
    type: 'number',
    flex: 1,
  },
  {
    headerName: __('Workspace owner'),
    field: 'created_by',
    flex: 1,
  },
]

export default WorkspacesIndexTableHeader
