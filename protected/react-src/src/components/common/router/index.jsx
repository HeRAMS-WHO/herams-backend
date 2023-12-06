import location from "../../../states/location"
import { useEffect } from "react"
const Router = ({children}) => {
    useEffect(() => {
        const changeUrl = () => {
            location.value = window.location.pathname
        }
        window.addEventListener('popstate', changeUrl)
        return () => window.removeEventListener('popstate', changeUrl)
    }, [])

    return (<>
        {children}
    </>)
}

const useNavigate = () => {
    return (url) => {
        window.history.pushState({}, '', url)
        location.value = url
    }
}

const Link = ({to, children, className = '', ...props}) => {
    const navigate = useNavigate()
    const onClick = (e) => {
        if (e.metaKey || e.ctrlKey) return;
        if (e.button !== 0) return;
        e.preventDefault()
        navigate(to)
    }
    return (<>
        <a 
            href={to} 
            onClick={onClick} 
            className={className} 
            {...props}>
            {children}
        </a>
    </>)
}

export {Link, Router, useNavigate}