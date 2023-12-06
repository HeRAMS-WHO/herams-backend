import './index.css'
import { useEffect, useState } from 'react';
import DropdownInput from '../../common/form/DropdownInput';
import { fetchLocales } from '../../../services/apiProxyService';

const Header = () => {
    return (
        <div className="bg-black-web grid-container text-white px-2">
            <div className='logo'>
                <Link to="/">
                    <img 
                        src="https://v2.herams-staging.org/img/HeRAMS_white.svg" 
                        alt="logo" 
                        className="logo" />
                </Link>
            </div>
            <div className='d-flex place-item-center-vertical'>
                
            </div>
            <HeaderRightPanel />
        </div>
    )    
}

const HeaderRightPanel = () => {
    const changeLanguage = (language) => {
        languageSelected.value = language
    }
    const [languages, setLanguages] = useState([])
    useEffect(() => {
        fetchLocales().then((response) => {
            setLanguages(response.map((locale) => (
                {value:locale.locale.toLowerCase(), label: locale.locale.toUpperCase()}
            )))
        })
    }, [])
    return <div className="d-flex place-item-center-vertical justify-content-end">
        <div className='w-75 text-align-right'>
            <Link className="no-style mx-4px text-white" to="/admin/project"><i className="material-icons">settings</i></Link>
            <Link className="no-style mx-4px text-white" to="/"><i className="material-icons">home</i></Link>
            <Link className="no-style mx-4px text-white" to="#"><i className="material-icons">favorite</i></Link>
            <Link className="no-style mx-4px text-white" to="#"><i className="material-icons">account_circle</i></Link>
            <Link className="no-style mx-4px text-white" to="#"><i className="material-icons">help</i></Link>
            <Link className="no-style mx-4px text-white" to="/session/delete"><i className="material-icons">logout</i></Link>
        </div>
        <div className='w-25 pl-25px'>
            <span className='d-flex border-radius-60px'>
                <span className='d-block-inline'>
                    <i className="material-icons">public</i>
                </span>
                <DropdownInput className='borderless ml-20px bg-black-web '
                    options={languages}
                    value={languageSelected.value}
                    onChange={(e) => changeLanguage(e.target.value)}  />
            </span>
        </div>
    </div>
}
export default Header
