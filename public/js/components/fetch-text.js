'use strict';

export default class FetchText extends HTMLElement {

    constructor() {
        super();
        this.#shadowRoot = this.attachShadow({'mode': 'open'});
        this.#shadowRoot.textContent = 'abc';
    }
    set uri(value) {

    }

    static get observedAttributes()
    {
        return ['name', 'uri'];
    }


    attributeChangedCallback(name, oldValue, newValue)
    {
        // todo
    }

    #disconnect()
    {
    }
    connectedCallback()
    {
        // Listen for data
    }

}

customElements.define('fetch-text',   FetchText);
