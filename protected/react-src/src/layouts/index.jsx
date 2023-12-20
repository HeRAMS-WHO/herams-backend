import AdminLayout from "./AdminLayout";
import AuthLayout from "./AuthLayout";
const Layouts = ({ layout, Page, routes }) => {
    switch (layout) {
        case 'AuthLayout':
            return <AuthLayout Page={Page} />;
        case 'AdminLayout':
        default:
            return <AdminLayout Page={Page} routes={routes} />;
    }
};
export default Layouts;