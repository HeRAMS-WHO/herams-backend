const goToParent= () => {
    const urlBack = window.location.pathname.split('/').slice(0, -1).join('/');
    useNavigate()(urlBack)
}
export default goToParent