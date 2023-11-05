import useGlobalUserRoles from "../../hooks/userRoles/useGlobalUserRoles";
import {__} from "../../utils/translationsUtility";
import TextInput from "../common/form/TextInput";
import FormGroup from "../common/form/FormGroup";

const GlobalUserRoles = ({userId}) => {
    const { userInfo } = useGlobalUserRoles({userId})

    return (
        <div className="container-fluid px-2">
            <div className="row mt-2">
                <div className="col-md-12">
                    <h1 className="mt-3">
                        {userInfo.name}
                    </h1>
                </div>
            </div>
            <div className="row mt-2">
                <form className="w-100">
                    <div>
                        <FormGroup label={__("Name")} inputClassName='col-md-6'>
                            <TextInput
                                value={userInfo.name}
                                className="form-control"
                                disabled={true}
                            />
                        </FormGroup>
                        <FormGroup label={__("Email")} inputClassName='col-md-6'>
                            <TextInput
                                value={userInfo.email}
                                className="form-control"
                                disabled={true}
                            />
                        </FormGroup>
                        <FormGroup label={__("Created date")} inputClassName='col-md-6'>
                            <TextInput
                                value={userInfo.created_date}
                                className="form-control"
                                disabled={true}
                            />
                        </FormGroup>
                        <FormGroup label={__("Created by")} inputClassName='col-md-6'>
                            <TextInput
                                value={userInfo?.creator?.name}
                                className="form-control"
                                disabled={true}
                            />
                        </FormGroup>
                        <FormGroup label={__("Latest update date")} inputClassName='col-md-6'>
                            <TextInput
                                value={userInfo.last_modified_date}
                                className="form-control"
                                disabled={true}
                            />
                        </FormGroup>
                        <FormGroup label={__("Last update by")} inputClassName='col-md-6'>
                            <TextInput
                                value={userInfo?.updater?.name}
                                className="form-control"
                                disabled={true}
                            />
                        </FormGroup>
                        <FormGroup label={__("Last login date")} inputClassName='col-md-6'>
                            <TextInput
                                value={userInfo.last_login_date}
                                className="form-control"
                                disabled={true}
                            />
                        </FormGroup>
                    </div>
                </form>
            </div>
        </div>
    );
}

export default GlobalUserRoles;