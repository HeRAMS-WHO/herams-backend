import './HomeLayout.css'

const HomeLayout = ({ routes, Page }) => {
    return (<>
        {Page && <Page />}
    </>)
}

export default HomeLayout