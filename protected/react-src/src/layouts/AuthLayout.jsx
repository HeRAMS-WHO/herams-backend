import './AuthLayout.css'
const AuthLayout = ({ routes, Page }) => {
    return (<>
        {Page && <Page />}
    </>)
}

export default AuthLayout