import AdminLayout from "./AdminLayout";
const Layouts = ({ layout, Page, routes }) => {
    switch (layout) {
        case 'AdminLayout':
        default:
            return <AdminLayout Page={Page} routes={routes} />;
    }
};
export default Layouts;