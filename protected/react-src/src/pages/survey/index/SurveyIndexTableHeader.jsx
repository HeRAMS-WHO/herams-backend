import { GridActionsCellItem } from "@mui/x-data-grid";
import DeleteIcon from '@mui/icons-material/Delete';
import { deleteSurvey } from "../../../services/apiProxyService";

const SurveyIndexTableHeader = ({refreshTable = () => {}}) => [
    {
        headerClassName: 'material_table_header_style',
        headerName: __('Id'),
        field: 'id',
        type: 'number',
        width: 80
    },
    {
        renderCell: (params) => (
            <Link to={replaceVariablesAsText( `/admin/survey/${params.id}`)}>{params.value}</Link>
        ),
        headerClassName: 'material_table_header_style',
        headerName: __('Title'),
        field: 'title',
        flex: 1
    },
    {
        headerClassName: 'material_table_header_style',
        headerName: __('Actions'),
        field: 'actions',
        type: 'actions',
        flex: 1,
        getActions: (params) => [
          <GridActionsCellItem icon={<DeleteIcon/>} onClick={() => {
            deleteSurvey(params.id).then(() => {
                refreshTable()
            })
          }} label={__('Delete')}/>,
        ],
      },
];

export default SurveyIndexTableHeader
