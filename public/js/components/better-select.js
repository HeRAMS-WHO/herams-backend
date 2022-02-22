'use strict';

export default class BetterSelect extends HTMLElement {
    #internals;
    #shadow;

    #autoMultiple = false;

    // The element the user last interacted with
    #lastInteractedElement;

    // The element the user currently has the mouse over
    #currentElement;



    static get formAssociated()
    {
        return true;
    }

    static get observedAttributes()
    {
        return ['value', 'automultiple'];
    }

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
        return this.#internals.setValidity({customError: true}, msg);
    }


    set value(newValue)
    {
        this.querySelectorAll('[value]').forEach(elem => {
            elem.toggleAttribute('selected', newValue.includes(elem.value))
        });
    }

    get value()
    {
        let result = [];
        this.querySelectorAll('[selected]').forEach((option) => result.push(option.value));
        return result;
    }

    constructor()
    {
        super();
        this.#internals = this.attachInternals();
        this.tabIndex = 0;
        this.#shadow = this.attachShadow({ mode: "open"});
        this.#shadow.innerHTML = `
        <style>
        :host {
            display: inline-block;
            vertical-align: top;
            overflow-y: scroll;
            --selected-background-color: rgb(206, 206, 206);
            --selected-focused-background-color: rgb(30,144, 255);
            --will-select-background-color: rgba(0, 255,0, 0.3);
            --will-deselect-background-color: rgba(255, 0,0, 0.3);
            --selected-color: white;
        }
     
        ::slotted([value]) {
            user-select: none;
        }
     
        ::slotted(*) {
            display: block;
        }
     
        :host(:focus-within) ::slotted([selected]) {
            background-color: var(--selected-focused-background-color);
            color: var(--selected-color);
        }
    
        ::slotted([selected]) {
            background-color: var(--selected-background-color);
        }
     
        ::slotted(.will-select:not([selected])) {
            background-color: var(--will-select-background-color);
        
        }
        ::slotted(.will-deselect[selected]) {
            background-color: var(--will-deselect-background-color) !important;
        }
     
     
        </style>

        <slot></slot>`;
    }

    attributeChangedCallback(name, oldValue, newValue)
    {
        switch (name) {
            case 'automultiple':
                this.#autoMultiple = newValue !== null;
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

    #insertData(formData)
    {
        this.querySelectorAll('[selected]').forEach(option=> formData.append(this.name, option.value));
    }

    formAssociatedCallback(form)
    {
        const insertData = this.#insertData;
        form.addEventListener('formdata', (e) => insertData.call(this, e.formData));



    }

    #updateValidity()
    {
        this.#internals.setValidity({customError: false});
    }


    #handleClick(e)
    {
        let option = e.target.closest('[value]');
        if (!option) {
            return;
        }


        if (this.#lastInteractedElement && e.shiftKey) {
            // Change everything in between.
            let current;
            let last;
            if (document.DOCUMENT_POSITION_PRECEDING & option.compareDocumentPosition(this.#lastInteractedElement)) {
                current = this.#lastInteractedElement;
                last = option;
            } else {
                last = this.#lastInteractedElement;
                current = option;
            }
            // New state:
            let desiredState = this.#lastInteractedElement.hasAttribute('selected');
            while (current !== last) {
                current.toggleAttribute('selected', desiredState);
                current = current.nextElementSibling;
            }
            current.toggleAttribute('selected', desiredState);
        } else if (!e.ctrlKey && !this.#autoMultiple) {
            this.querySelectorAll('[selected]').forEach((e) => e.removeAttribute('selected'));
            option.toggleAttribute('selected', true);
        } else {
            option.toggleAttribute('selected');
        }
        this.#lastInteractedElement = option;

    }

    #currentElementChanged()
    {
        if (!(this.#currentElement && this.#lastInteractedElement)) {
            return;
        }
        let option = this.#currentElement;
        let current;
        let last;
        if (document.DOCUMENT_POSITION_PRECEDING & option.compareDocumentPosition(this.#lastInteractedElement)) {
            current = this.#lastInteractedElement.nextElementSibling;
            last = option;
        } else {
            last = this.#lastInteractedElement.previousElementSibling;
            current = option;
        }

        this.querySelectorAll('[value]').forEach(e => e.classList.remove('will-select', 'will-deselect'));

        // Abort if we are over the current element
        if (this.#lastInteractedElement === this.#currentElement || this.#currentElement === null) {
            return;
        }
        let newClass = this.#lastInteractedElement.hasAttribute('selected') ? 'will-select' : 'will-deselect';
        while (current !== last) {
            current.classList.add(newClass);
            current = current.nextElementSibling;
        }
        current.classList.add(newClass);
    }

    connectedCallback()
    {
        this.addEventListener('mouseover', (e) => {
            this.#currentElement = e.target.closest('[value]');
            if (e.shiftKey) {
                this.#currentElementChanged();
            }
        });
        this.addEventListener('mouseleave', (e) => {
            this.#currentElement = null;
            if (e.shiftKey) {
                this.#currentElementChanged();
            }

        });
        this.addEventListener('click', this.#handleClick);
        this.addEventListener('keydown', (e) => {
            if (e.key !== 'Shift') {
                return;
            }
            if (e.shiftKey) {
                this.#currentElementChanged();
            }


        });

        this.addEventListener('keyup', (e) => {
            if (e.key !== 'Shift') {
                return;
            }
            this.querySelectorAll('[value]').forEach(e => e.classList.remove('will-select', 'will-deselect'));
        });


    }

}

customElements.define('better-select',   BetterSelect);
