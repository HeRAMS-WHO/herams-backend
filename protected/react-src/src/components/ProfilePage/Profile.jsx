import {
    useEffect,
    useState
} from 'react';

import { formatObjectToValueLabel } from '../../utils/objectUtils';
import * as apiService from '../../services/apiService';
import { saveTranslations, __, clearTranslations } from '../../utils/translationsUtility';

import TextInput from '../common/form/TextInput';
import DropdownInput from '../common/form/DropdownInput';
import CheckboxInput from '../common/form/CheckboxInput';
import FormButtons from '../common/form/FormButtons';
import FormGroup from '../common/form/FormGroup';


const Profile = (props) => {

    const decodedUserData = atob(props.user); // Decoding base64 data
    const initialUser = JSON.parse(decodedUserData); // Assuming the base64 data was a stringified JSON

    const [name, setName] = useState(initialUser.name || '');
    const [language, setLanguage] = useState(initialUser.language || localStorage.getItem('selectedLanguage') || 'en');
    const [newsletterSubscription, setNewsletterSubscription] = useState(initialUser.newsletter_subscription || false);

    const [availableLanguages, setAvailableLanguages] = useState([]); // <-- Added this state to hold available languages
    // const translations = JSON.parse(localStorage.getItem('translations') || '{}');


    useEffect(() => {
        fetchAndSetAvailableLanguages();
    }, []);

    const fetchAndSetAvailableLanguages = (responseData = null) => {
        let availableLangs = {};

        if (responseData && responseData.languages) {
            availableLangs = responseData.languages;
        } else {
            // If not provided in the response data, fetch from localStorage
            availableLangs = JSON.parse(localStorage.getItem('availableLanguages') || '{}');
        }

        const formattedLanguages = formatObjectToValueLabel(availableLangs);

        setAvailableLanguages(formattedLanguages);
    };



    const handleModifyProfile = async () => {
        const data = new URLSearchParams();
        data.append('User[name]', name);
        data.append('User[language]', language);
        data.append('User[newsletter_subscription]', newsletterSubscription ? '1' : '0');

        try {
            let headers = {
                'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',  // Ensure you have the correct content type
            };
            const response = await apiService.updateProfile(data, headers);

            const responseData = await response.json();

            if (responseData.status === 'success') {
                await fetchAndSetAvailableLanguages(responseData);
                localStorage.setItem('selectedLanguage', language);
                // Store translations in localStorage
                clearTranslations();
                saveTranslations(responseData.translations);
                // If the update was successful, refresh the page
                window.location.reload(true); // The `true` argument forces a hard reload from the server
            } else {
                // Optionally handle other statuses or errors in the response
                console.error('Update was not successful:', responseData);
            }

        } catch (error) {
            console.error('Error in handleModifyProfile:', error);
            // Handle other errors like network issues, etc.
        }
    };


    return (
        <form id="w0" className="form-vertical kv-form-bs3">
            <FormGroup label={__("Name")}>
                <TextInput
                    id="user-name"
                    className="form-control"
                    name="User[name]"
                    value={name}
                    onChange={(e) => setName(e.target.value)}
                />
            </FormGroup>
            <FormGroup label={__("Language")}>
                <DropdownInput
                    id="user-language"
                    className="form-control"
                    name="User[language]"
                    value={language}
                    onChange={(e) => setLanguage(e.target.value)}
                    options={availableLanguages}
                />
            </FormGroup>
            <FormGroup label={__("Newsletter subscription")}>
                <CheckboxInput
                    id="user-newsletter_subscription"
                    name="User[newsletter_subscription]"
                    checked={newsletterSubscription}
                    onChange={(isChecked) => setNewsletterSubscription(isChecked)}
                />
            </FormGroup>
            <FormButtons
                buttons={[
                    {
                        label: __("Update profile"),
                        class: "btn btn-primary",
                        onClick: handleModifyProfile
                    }
                ]}
            />
        </form>
    );
};

export default Profile;
