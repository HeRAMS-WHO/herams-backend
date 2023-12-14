import location from "../../../states/location"
import { useEffect } from "react"
import Button from "@mui/material/Button";
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

// Your custom Link component
const Link = ({ to, children, className = '', ...props }) => {
    const navigate = useNavigate();
    const onClick = (e) => {
        if (e.metaKey || e.ctrlKey) return;
        if (e.button !== 0) return;
        e.preventDefault();
        navigate(to);
    };
    return (
        <a href={to} onClick={onClick} className={className} {...props}>
            {children}
        </a>
    );
};

// Custom LinkButton component
const LinkButton = ({ to, label, icon, className, ...props }) => {
    return (
        <Link to={to} className={className}>
            <Button {...props} startIcon={icon}>
                {label}
            </Button>
        </Link>
    );
};

export {Link, Router, useNavigate, LinkButton}
