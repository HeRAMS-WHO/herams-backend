import { __ } from '../../../utils/translationsUtility'
import { UserIcon } from '../../../components/common/icon/IconsSet'
import { GridActionsCellItem } from '@mui/x-data-grid'
import DeleteIcon from '@mui/icons-material/Delete'
import Link from '@mui/material/Link'
import dayjs from 'dayjs'

const UserIndexTableHeader = () => [
  {
    headerName: __('Id'),
    field: 'id',
    type: 'number',
    width: 80,
  },
  {
    headerName: __('Name'),
    renderCell: (params) => (
      <Link href={`/admin/user/${params.id}`}>{params.value}</Link>
    ),
    field: 'name',
    flex: 1,
  },
  {
    headerName: __('Email'),
    field: 'email',
    flex: 1,
  },
  {
    headerName: __('Created on'),
    field: 'created_date',
    type: 'date',
    flex: 1,
    valueFormatter: (params) => params.value && dayjs(params.value).format('YYYY-MM-DD HH:mm'),
  },
  {
    headerName: __('Last login'),
    field: 'last_login_date',
    type: 'date',
    flex: 1,
    valueFormatter: (params) => params.value && dayjs(params.value).format('YYYY-MM-DD HH:mm'),
  },
  {
    headerName: __('Actions'),
    field: 'actions',
    type: 'actions',
    flex: 1,
    getActions: (params) => [
      <GridActionsCellItem icon={<UserIcon/>} onClick={() => console.log('impersonate id ' + params.id)}
                           label={__('Impersonate')}/>,
      <GridActionsCellItem icon={<DeleteIcon/>} onClick={() => console.log('delete id ' + params.id)}
                           label={__('Delete User')}/>,
    ],
  },
]

export default UserIndexTableHeader
