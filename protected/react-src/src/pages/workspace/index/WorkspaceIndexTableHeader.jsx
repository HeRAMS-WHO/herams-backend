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
    renderCell: (params) => {
      const titles = params?.row?.i18n?.title
      const title = titles && titles[languageSelected.value] ? titles[languageSelected.value] : titles?.en
      return (<Link to={`/admin/project/${params.row.project_id}/workspace/${params.row.id}/HSDU`}>{title ?? 'No name in selected language' }</Link>)
    },
    field: 'name',
    flex: 1,
  },
  {
    headerName: __('Date of update'),
    field: 'date_of_update',
    type: 'date',
    flex: 1,
    renderCell: (params) => {
      return <Time time={params.row.date_of_update} />
    },
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
