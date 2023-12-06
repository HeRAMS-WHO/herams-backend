import Header from "../components/common/Navbar";
import TabsMenu from "../components/common/TabMenu";
import Breadcrumb from "../components/common/Breadcrumb";

const AdminLayout = ({ routes, Page }) => {
    return (<>
        <Header />
        <TitleContainer>
            <HeaderTitle />
        </TitleContainer>
        <BreadcrumbContainer>
            <Breadcrumb routes={routes} />
        </BreadcrumbContainer>
        <TabContainer>
            <TabsMenu routes={routes} />
        </TabContainer>
        <MainContent>
            {Page && <Page />}
        </MainContent>
    </>)
}

const MainContent = ({children}) => {
    return (
        <div className="col-md-8 mx-auto bg-white col-12 py-2 px-2">
            {children}
        </div>
    )
}
const BreadcrumbContainer = ({children}) => {
    return (
        <div className="col-md-8 mx-auto col-12 mt-2 mb-2 p-0">
            {children}
        </div>
    )
}
const TabContainer = ({children}) => {
    return (
        <div className="col-md-8 mx-auto col-12 mt-0 p-0">
            {children}
        </div>
    )
}
const TitleContainer = ({children}) => {
    return (
        <div className="col-md-8 mx-auto col-12 mt-0 p-0">
            {children}
        </div>
    )
}

const HeaderTitle = () => {
    return (
        <>
            <div className="mb-2">
                <h1 className='h2 mb-0'>
                    {replaceVariablesAsText(routeInfo.value.pageTitle)}
                </h1>
            </div>
        </>
    )
}

export default AdminLayout