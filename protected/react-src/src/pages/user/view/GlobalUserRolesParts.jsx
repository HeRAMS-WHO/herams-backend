import * as PropTypes from "prop-types";
import FormGroup from "../../../components/common/form/FormGroup";
import {__} from "../../../utils/translationsUtility";
import TextInput from "../../../components/common/form/TextInput";

export function TitleGlobalUserRoles(props) {
    return <div className="row mt-2">
        <div className="col-md-12">
            <h1 className="mt-3">
                {props.userInfo.name}
            </h1>
        </div>
    </div>;
}

TitleGlobalUserRoles.propTypes = {userInfo: PropTypes.any};

export function BasicUserInfo(props) {
    console.log(props)
    return <div className="row mt-2">
        <form className="w-100">
            <div>
                <FormGroup label={__("Name")} inputClassName="col-md-6">
                    <TextInput
                        value={props.userInfo.name ?? ""}
                        className="form-control"
                        disabled={true}
                    />
                </FormGroup>
                <FormGroup label={__("Email")} inputClassName="col-md-6">
                    <TextInput
                        value={props.userInfo.email ?? ""}
                        className="form-control"
                        disabled={true}
                    />
                </FormGroup>
                <FormGroup label={__("Created date")} inputClassName="col-md-6">
                    <TextInput
                        value={props.userInfo.created_at ?? ""}
                        className="form-control"
                        disabled={true}
                    />
                </FormGroup>
                <FormGroup label={__("Created by")} inputClassName="col-md-6">
                    <TextInput
                        value={(props.userInfo?.creator?.name !== undefined) ? props.userInfo?.creator?.name : ""}
                        className="form-control"
                        disabled={true}
                    />
                </FormGroup>
                <FormGroup label={__("Latest update date")} inputClassName="col-md-6">
                    <TextInput
                        value={props.userInfo.updated_at ?? ""}
                        className="form-control"
                        disabled={true}
                    />
                </FormGroup>
                <FormGroup label={__("Last update by")} inputClassName="col-md-6">
                    <TextInput
                        value={(props.userInfo?.updater?.name !== undefined) ? props.userInfo?.updater?.name : ""}
                        className="form-control"
                        disabled={true}
                    />
                </FormGroup>
                <FormGroup label={__("Last login date")} inputClassName="col-md-6">
                    <TextInput
                        value={props.userInfo.last_login_at ?? ""}
                        className="form-control"
                        disabled={true}
                    />
                </FormGroup>
            </div>
        </form>
    </div>;
}

BasicUserInfo.propTypes = {userInfo: PropTypes.any};