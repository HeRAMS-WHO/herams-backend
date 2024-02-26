import AdminLayout from "./AdminLayout";
import AuthLayout from "./AuthLayout";
import HomeLayout from "./HomeLayout";

const Layouts = ({ layout, Page, routes }) => {
    switch (layout) {
        case 'AuthLayout':
            return <AuthLayout Page={Page} />;
        case 'HomeLayout':
            return <HomeLayout Page={Page} />;
        case 'AdminLayout':
        default:
            return <AdminLayout Page={Page} routes={routes} />;
    }
};
export default Layouts;