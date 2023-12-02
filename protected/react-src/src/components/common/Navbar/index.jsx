import './index.css'
import params from '../../../states/params';
import routeInfo from '../../../states/routeInfo';
import info, {specialVariables} from '../../../states/info';
import { useEffect, useState } from 'react';
import languageSelected from '../../../states/languageSelected';
import DropdownInput from '../../common/form/DropdownInput';
const selectedPage = 'Roles'

const pages = {
    'Roles': {'breadcrumb': [{'title': 'Roles', 'page': 'Roles'}]},
};

const T = ({value}) => {
    const [translation, setTranslation] = useState('')
    useEffect(() => {
        let tempTranslation = value
        specialVariables?.value?.keys?.forEach((key) => {
            tempTranslation = tempTranslation?.replaceAll(key, specialVariables.value?.translations?.[key]);
        });
        setTranslation(tempTranslation);
    }, [specialVariables.value, languageSelected.value])
    return <>{translation}</>
}

const HeaderTitle = () => {
    return (
        <>
            <h1 className='h2 mb-0 active-link'><T value={routeInfo.value.pageTitle} /></h1>
        </>
    )
}
const Header = () => {
    return (
        <div className="header">
            <div className='d-flex'>
                <div className="header--left-panel">
                    <BreadCrumb />
                </div>
                <HeaderRightPanel />
            </div>
            <div className="col-md-8 mx-auto col-12">
                <HeaderTitle />
            </div>
        </div>
    )    
}

const BreadCrumb = () => {
    return <div className="header--left-panel--breadcrumb">
        {
            pages[selectedPage].breadcrumb.map((item, index) => {
                return <span key={item.title}>
                        <a href="#" onClick={() => selectedPage.value = item.page}>{item.title}</a>
                        {index < pages[selectedPage].breadcrumb.length - 1 ? ' > ' : ''}
                    </span>
            })
        }
    </div>
}



const HeaderRightPanel = () => {
    const changeLanguage = (language) => {
        languageSelected.value = language
    }
    return <div className="header--rigth-panel d-flex place-item-center-vertical">
        <div>
            <div>
                <a className="no-style mx-4px" href="#"><i className="material-icons">cloud</i></a>
                <a className="no-style mx-4px" href="#"><i className="material-icons">favorite</i></a>
                <a className="no-style mx-4px" href="#"><i className="material-icons">attachment</i></a>
                <a className="no-style mx-4px" href="#"><i className="material-icons">computer</i></a>
                <a className="no-style mx-4px" href="#"><i className="material-icons">traffic</i></a>
            </div>
            <div className='bg-white text-dark d-relative border-radius-60px'>
                <span className='pt-8px d-block-inline d-centered'>
                    <i className="material-icons">public</i>
                </span>
                <DropdownInput className='py-6px borderless bg-transparent ml-30px'
                    options={[
                        {value: 'en', label: 'English'},
                        {value: 'es', label: 'Spanish'},
                        {value: 'fr', label: 'French'},
                        {value: 'de', label: 'German'},
                        {value: 'it', label: 'Italian'},
                        {value: 'pt', label: 'Portuguese'},
                        {value: 'ru', label: 'Russian'},
                        {value: 'zh', label: 'Chinese'},
                    ]}
                    value={languageSelected.value}
                    onChange={(e) => changeLanguage(e.target.value)}  />
            </div>
        </div>
        
    </div>
}
export default Header
