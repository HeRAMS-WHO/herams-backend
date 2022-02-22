'use strict';

export default class ValidationMessage extends HTMLElement {

    #input;
    #originalSetCustomValidity;

    static get formAssociated()
    {
        return false; }
    static get observedAttributes()
    {
        return ['for']; }

    constructor()
    {
        super();
    }

    attributeChangedCallback(name, oldValue, newValue)
    {
        switch (name) {
            case 'for':
                this.#connect(newValue);
                break;
        }
    }

    #disconnect()
    {
        if (this.#input) {
            let input = this.#input;
            this.#input = null;
            input.removeEventListener("input", this.#handleInput);
            input.removeEventListener("invalid", this.#handleInput);
            input.setCustomValidity = this.#originalSetCustomValidity;
        }
    }

    #handleInput()
    {
        // Check if element is valid
        if (!this.#input.validity.valid) {
            this.innerText = this.#input.validationMessage;
        } else {
            this.innerText = '';
        }
    }


    async #connect(id)
    {
        let input = document.getElementById(id);

        if (!input) {
            return;
        }

        if (!input.type) {
            await customElements.whenDefined(input.localName);
        }

        this.#disconnect();

        input.addEventListener("blur", () => { this.#handleInput(); });

        // We want to know when the input becomes invalid
        input.addEventListener("invalid", (e) => {
            if (!this.hasAttribute('enable-native')) {
                e.preventDefault();
            }
            this.#handleInput();
        });

        /**
         * We want to know when the input becomes valid; this can happen through input as well as through calling
         * setCustomValidity. Therefore we proxy the function
         */

        this.#originalSetCustomValidity = input.setCustomValidity;
        input.setCustomValidity = (message) => {
            Object.getPrototypeOf(input).setCustomValidity.call(input, message);
            this.#handleInput();
        }
        this.#input = input;

    }

    connectedCallback()
    {
    }

}

customElements.define('validation-message',   ValidationMessage);