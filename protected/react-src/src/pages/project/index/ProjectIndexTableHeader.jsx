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
    renderCell: (params) => {
      return (<Link to={replaceVariablesAsText(`/admin/project/${params.id}/workspace`)}>{params?.row?.i18n?.title[languageSelected.value] ?? params?.row?.i18n?.title['en'] }</Link>)
    },
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
    field: 'totalContributors',
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
