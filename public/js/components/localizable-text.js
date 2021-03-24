'use strict';

export default class LocalizableTextInput extends HTMLElement {
    #shadow;

    #rows = {};

    #inputs = {};
    #labels = {};

    #internals;

    static get formAssociated()
    {
        return true; }
    static get observedAttributes()
    {
        return ['value', 'languages']; }
    get form()
    {
        return this.#internals.form; }
    get name()
    {
        return this.getAttribute('name'); }
    get type()
    {
        return this.localName; }

    get validity()
    {
        return this.#internals.validity;
    }
    get validationMessage()
    {
        return this.#internals.validationMessage; }
    get willValidate()
    {
        return this.#internals.willValidate; }

    checkValidity()
    {
        return this.#internals.checkValidity();
    }
    reportValidity()
    {
        return this.#internals.reportValidity(); }

    setCustomValidity(msg)
    {
        console.log('setting custom validity');
        return this.#internals.setValidity({customError: true}, msg);
    }


    set value(json)
    {
        const parsed = JSON.parse(json);
        for (let lang in parsed) {
            if (!this.#inputs[lang]) {
                this.#addLanguage(lang, lang);
            }
            this.#inputs[lang].value = parsed[lang];
        }
    }
    get value()
    {
        const value = {};
        for (let lang in this.#inputs) {
            if (this.#inputs[lang].value) {
                value[lang] = this.#inputs[lang].value;
            }
        }
        return JSON.stringify(value)
    }


    constructor()
    {
        super();
        this.#internals = this.attachInternals();
    }

    attributeChangedCallback(name, oldValue, newValue)
    {
        console.log('attributeChangedCallback', name, newValue)
        switch (name) {
            case 'languages':
                this.#updateLanguages(oldValue, newValue);
                break;
            case 'value':
                this.value = newValue;
                break;
        }
    }

    formStateRestoreCallback(...args)
    {
        console.log('formStateRestoreCallback', args);
    }

    formResetCallback(...args)
    {
        console.log('formResetCallback', args);
    }

    formAssociatedCallback(...args)
    {
        console.log('formAssociatedCallback', args);
    }

    #updateValidity()
    {
        for (let i in this.#inputs) {
            if (!this.#inputs[i].validity.valid) {
                this.#internals.setValidity({customError: true}, this.#inputs[i].validationMessage);
                return;
            }
        }
        this.#internals.setValidity({customError: false});
    }

    #addLanguage(language, labelText)
    {
        const inputID = CSS.escape('input-' + language);
        // this.#shadow.querySelectorAll("[select=" + CSS.escape(language) + "]")
        const row = document.createElement('div');
        // row.classList.add('form-group');
        this.#rows[language] = row;

        const label = document.createElement('label');
        label.innerText = labelText;

        label.setAttribute('for', inputID);
        row.append(label);
        this.#labels[language] = label;

        const input = document.createElement('input');
        input.type = 'text';
        input.pattern = '\\w+';
        input.id = inputID;
        input.addEventListener('change', () => {
            this.#internals.setFormValue(this.value);
            this.#updateValidity();
        });

        input.addEventListener('blur', (e) => {
            if (!e.relatedTarget || !this.contains(e.relatedTarget)) {
                this.dispatchEvent(new FocusEvent('blur'));
            }
        });

        row.append(input);
        this.#inputs[language] = input;

        this.append(row);

    }

    #updateLanguages(oldValue, newValue)
    {
        const parsed = JSON.parse(newValue);
        const languages = [];
        const labels = {};
        for (let i in parsed) {
            languages.push(i.trim());
            labels[i.trim()] = parsed[i];
        }
        // Remove languages
        for (let language in this.#rows) {
            if (!languages.includes(language)) {
                this.#rows[language].parentNode.removeChild(this.#rows[language]);
                delete this.#rows[language];
                delete this.#labels[language];
                delete this.#inputs[language];
            }
        }
        languages.forEach((language) => {
            if (!this.#rows[language]) {
                this.#addLanguage(language, labels[language]);
            } else {
                // Existing language
                this.#labels[language].innerText = labels[language];
            }
        });
    }

    connectedCallback()
    {
        this.style.display = 'inline-block';
        if (!this.hasAttribute('tabindex')) {
            this.setAttribute('tabindex', 0);
        }
        console.log('comp connected');

    }

}

customElements.define('localizable-input',   LocalizableTextInput);
