import translations from './translation.json';

// Attempt to get the saved language from localStorage or fall back to a default.
let currentLanguage = localStorage.getItem('appLanguage') || 'default';

export const setLanguage = (lang) => {
    currentLanguage = lang;
    localStorage.setItem('appLanguage', lang);
}

export const __ = (text) => {
    if (translations[currentLanguage] && translations[currentLanguage][text]) {
        return translations[currentLanguage][text];
    }
    return text;
};
