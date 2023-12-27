import {__} from "../../../utils/translationsUtility";
import replaceVariablesAsText from "../../../utils/replaceVariablesAsText";

const CustomLinkRenderer = ({data}) => {
    const link = `/admin/user/${data.id}`;
    return (
        <Link to={replaceVariablesAsText( `/admin/survey/${data.id}`)} className={"agGridAnkur"}>{data.title}</Link>
    );
};

const SurveyIndexTableHeader = () => [
    {
        headerName: __('Title'),
        checkboxSelection: false,
        field: 'title',
        filter: true,
        width: 200,
        sortable: true,
        cellRenderer: CustomLinkRenderer,
        comparator: (a, b) => a.localeCompare(b)
    },
    {
        headerName: __('Id'),
        field: 'id',
        checkboxSelection: false,
        filter: true,
        width: 100,
        sortable: true,
    },
];

export default SurveyIndexTableHeader
