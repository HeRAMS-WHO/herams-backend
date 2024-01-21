import { __ } from '../../../utils/translationsUtility'
import Link from '@mui/material/Link'
import replaceVariablesAsText from '../../../utils/replaceVariablesAsText'

const ProjectIndexTableHeader = () => [
  {
    headerClassName: 'material_table_header_style',
    headerName: __('Id'), // Added headerName
    field: 'id',
    type: 'number',
    width: 80,
  },
  {
    headerName: __('Project Name'),
    renderCell: (params) => (
      <Link href={replaceVariablesAsText(`/admin/project/${params.id}/workspace`)}>{params.value}</Link>
    ),
    headerClassName: 'material_table_header_style',
    field: 'name',
    flex: 1,
  },
  {
    headerClassName: 'material_table_header_style',
    headerName: __('Workspaces'),
    field: 'workspaceCount',
    type: 'number',
    flex: 1,
  },
  {
    headerClassName: 'material_table_header_style',
    headerName: __('Contributors'),
    field: 'contributorCount',
    type: 'number',
    flex: 1,
  },
  {
    headerClassName: 'material_table_header_style',
    headerName: __('HSDUs'),
    field: 'facilityCount',
    type: 'number',
    flex: 1,
  },
  {
    headerClassName: 'material_table_header_style',
    headerName: __('Responses'),
    field: 'responseCount',
    type: 'number',
    flex: 1,
  },
  {
    headerClassName: 'material_table_header_style',
    headerName: __('Project coordinator'),
    field: 'coordinatorName',
    flex: 1,
  },
]

export default ProjectIndexTableHeader
