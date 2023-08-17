import React, { useState } from 'react';
import TextInput from './form/TextInput';
import DropdownInput from './form/DropdownInput';
import CheckboxInput from './form/CheckboxInput';
import FormButtons from './form/FormButtons';

// A FormGroup to encapsulate input components with labels and errors.
const FormGroup = ({ label, error, children }) => (
    <div className="form-group highlight-addon">
        {label && <label className="form-label has-star">{label}</label>}
        {children}
        {error && <div className="help-block help-block-error">{error}</div>}
    </div>
);

const Profile = () => {
    const [name, setName] = useState('Girlea Cristian');
    const [language, setLanguage] = useState('fr');
    const [newsletterSubscription, setNewsletterSubscription] = useState(false);

    const handleSubmit = (e) => {
        e.preventDefault();
        // Do your submission logic here
    };

    return (
        <form id="w0" className="form-vertical kv-form-bs3" action="/user/profile" method="post" onSubmit={handleSubmit}>
            {/* CSRF input goes here */}

            <FormGroup label="Nom d'utilisateur">
                <TextInput
                    id="user-name"
                    className="form-control"
                    name="User[name]"
                    value={name}
                    onChange={(e) => setName(e.target.value)}
                />
            </FormGroup>

            <FormGroup label="Langue">
                <DropdownInput
                    id="user-language"
                    className="form-control"
                    name="User[language]"
                    value={language}
                    onChange={(e) => setLanguage(e.target.value)}
                    options={[
                        { value: "", label: "Autodétecté (en)" },
                        { value: "en", label: "English" },
                        { value: "ar-AR", label: "Arabic (Argentina)" },
                        { value: "ar", label: "Arabic" },
                        { value: "fr", label: "French" },
                        { value: "fr-FR", label: "French (France)" }
                    ]}
                />
            </FormGroup>

            <FormGroup label="Abonnement au bulletin d'information">
                <CheckboxInput
                    id="user-newsletter_subscription"
                    name="User[newsletter_subscription]"
                    checked={newsletterSubscription}
                    onChange={(e) => setNewsletterSubscription(e.target.checked)}
                />
            </FormGroup>

            <FormButtons primaryLabel="Modifier le profil" />
        </form>
    );
};

export default Profile;
