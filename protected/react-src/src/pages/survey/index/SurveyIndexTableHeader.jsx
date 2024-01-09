import {__} from "../../../utils/translationsUtility";
import Link from "@mui/material/Link";
import replaceVariablesAsText from "../../../utils/replaceVariablesAsText";

const SurveyIndexTableHeader = () => [
    {
        headerClassName: 'material_table_header_style',
        renderHeader: () => (
            <strong>{__('Id')}</strong>
        ),
        field: 'id',
        type: 'number',
        width: 80
    },
    {
        renderHeader: () => (
            <strong>{__('Title')}</strong>
        ),
        renderCell: (params) => (
            <Link href={replaceVariablesAsText( `/admin/survey/${params.id}`)}>{params.value}</Link>
        ),
        headerClassName: 'material_table_header_style',
        field: 'title',
        flex: 1
    }
];

export default SurveyIndexTableHeader
