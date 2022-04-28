/**
 * This contains a central place to interact with the API from the browser.
 * This keeps the logic all on the client side.
 */

class Herams {

    /**
     *
     * @param {HTMLElement} rootElement
     */
    constructor(rootElement) {
        rootElement.addEventListener('click', async(e) => {
            const element = e.target.closest('[data-herams-action]');
            if (element) {
                e.preventDefault();
                e.stopPropagation();
                return this.#triggerAction(element);
            }
        })

    }



    /**
     * @param {HTMLElement} element
     */
    #triggerAction(element)
    {
        const action = element.getAttribute('data-herams-action');
        // TODO
        // switch(action) {
        //     case 'create'
        // }
    }
    #getCsrfToken() {
        return yii.getCsrfToken();
    }

    #notifySuccess(message) {
        iziToast.success({
            position: "topRight",
            type:"success",
            message
        });
    }
    #notifyError(message) {
        iziToast.error(message);
    }

    async createElement(uri, plainObject, successMessage) {
        console.log('creating element', uri, plainObject);
        const response = await fetch(uri, {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            credentials: 'same-origin',
            headers: {
                'X-CSRF-Token': this.#getCsrfToken(),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(plainObject),
            redirect: 'error',
            referrer: 'no-referrer',
        });


        if (response.ok) {
            this.#notifySuccess(successMessage ?? 'Element created');
        } else {
            try {
                const body = await response.json();
                this.#notifyError(body.message);
            } catch (error) {
                this.#notifyError("Server couldn't handle command: " + response.statusText);
            }

        }
    }




}
export default Herams
