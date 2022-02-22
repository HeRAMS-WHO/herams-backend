'use strict';

const activeClass = 'active';

export default class TabbedContent extends HTMLElement {
    #shadow;

    constructor()
    {
        super();
        this.#shadow = this.attachShadow({ mode: "open"});
        this.#shadow.innerHTML = `
            <style>
                :host {
                    display: grid;
                    grid-template-rows: auto 1fr;
        }
                
                [part=content] {
                    overflow: auto;
        }
                
                [part=content] ::slotted(*) {
                    box-sizing: border-box !important;
                    height: 100%;
                    overflow: auto;
        }
                [part=content] ::slotted(:not(.active)) {
                    display: none;
        }
                
                [part=header] {
                    display: flex;
                    flex-direction: row;
                    flex-wrap: wrap;
                    justify-content: flex-start;
                    align-content: start;
                    gap: 10px;
        }
                
                [part=header] ::slotted(*) {
                    cursor:  pointer;
        }
                
                [part=header] ::slotted(*:hover) {
                   
        }
            </style>
            <div part="header">
            <slot name="header"></slot>
            </div>
            <div part="content">
                <slot name="content"></slot>
            </div>
        `;

        const contents = this.#shadow.querySelector('slot[name=content]');
        const headers = this.#shadow.querySelector('slot[name=header]');

        headers.addEventListener('click', (e) => this.#onHeaderClick(e, contents, headers));
        headers.addEventListener('slotchange', e => this.#onSlotChange(e.target));
    }


    activate(header)
    {
        if (header.classList.contains(activeClass)) {
            return;
        }
        header.classList.add(activeClass);
        header.nextElementSibling.classList.add(activeClass);
    }

    #onHeaderClick(e, tabs, headers)
    {
        // Identify link
        let header = e.target.closest('[slot=header]');
        if (header) {
            headers.assignedNodes().forEach(h => {
                h.classList.remove(activeClass);
                h.nextElementSibling.classList.remove(activeClass);
            });
            this.activate(header);
        }
    }

    #onSlotChange(slot)
    {
        if (slot.assignedNodes().length > 0 && !slot.assignedNodes().some((e) => e.classList.contains(activeClass))) {
            this.activate(slot.assignedNodes()[0]);
        }
    }
}

customElements.define('tabbed-content',   TabbedContent);