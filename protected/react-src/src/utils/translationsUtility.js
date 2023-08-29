const TRANSLATIONS_KEY = 'translations';

export const saveTranslations = (translations) => {
    try {
        const serializedTranslations = JSON.stringify(translations);
        localStorage.setItem(TRANSLATIONS_KEY, serializedTranslations);
    } catch (error) {
        console.error('Failed to save translations to localStorage:', error);
    }
};

export const getTranslation = (key) => {
    try {
        const serializedTranslations = localStorage.getItem(TRANSLATIONS_KEY);
        if (!serializedTranslations) return key; // No translations found

        const translations = JSON.parse(serializedTranslations);
        return translations[key] || key;
    } catch (error) {
        console.error('Failed to retrieve translation from localStorage:', error);
        return null;
    }
};

export const getAllTranslations = () => {
    try {
        const serializedTranslations = localStorage.getItem(TRANSLATIONS_KEY);
        if (!serializedTranslations) return {};

        return JSON.parse(serializedTranslations);
    } catch (error) {
        console.error('Failed to retrieve translations from localStorage:', error);
        return {};
    }
};

export const clearTranslations = () => {
    try {
        localStorage.removeItem(TRANSLATIONS_KEY);
    } catch (error) {
        console.error('Failed to clear translations from localStorage:', error);
    }
};

export const __ = getTranslation;
