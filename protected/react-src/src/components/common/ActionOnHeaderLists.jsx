import {__} from "../../utils/translationsUtility";
import AddBoxIcon from '@mui/icons-material/AddBox'
const ActionOnHeaderLists = ({ label, url }) => {
    return (
        <div style={{ display: 'flex', gap: '8px', marginTop: '16px', justifyContent: 'flex-end' }}>
            <LinkButton
                to={url}
                label={__(label)}
                icon={<AddBoxIcon />}
                variant="contained"
                style={{ width: '200px' }}
            />
        </div>
    );
};

export default ActionOnHeaderLists;
