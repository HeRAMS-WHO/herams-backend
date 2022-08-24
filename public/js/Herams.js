/**
 * This contains a central place to interact with the API from the browser.
 * This keeps the logic all on the client side.
 */
class ValidationError extends Error {
  errors = {}

  constructor (errors) {
    super('Validation failed')
    this.errors = errors
  }
}
class Herams {
  /**
   * @var {IziToast} iziToast
   */
  #iziToast

  #events = {}

  /**
   *
   * @param {HTMLElement} rootElement
   * @param {IziToast} iziToast
   */
  constructor (rootElement, iziToast) {
    this.#iziToast = iziToast
    rootElement.addEventListener('click', async (e) => {
      const element = e.target.closest('[data-herams-action]')
      if (element) {
        e.preventDefault()
        e.stopPropagation()
        return Herams.#triggerAction(element)
      }
    })
  }

  /**
   * @param {HTMLElement} element
   */
  static #triggerAction (element) {
    if (!element.hasAttribute('data-herams-action')) {
      return
    }

    const dataset = element.dataset
    // TODO
    switch (dataset.heramsAction) {
      case 'delete':
        Herams.#delete(element.dataset)
    }
  }

  async #confirm (message) {
    return new Promise((resolve, reject) => {
      this.#iziToast.question({
        message,
        overlay: true,
        overlayClose: true,
        color: 'red',
        closeOnEscape: true,
        position: 'center',
        buttons: [
          ['<button>Yes</button>', (instance, toast) => {
            instance.hide({}, toast, 'yes')
          }],
          ['<button>No</button>', (instance, toast) => {
            instance.hide({}, toast, 'no')
          }],
        ],
        onClosed: (instance, toast, closedBy) => {
          if (closedBy === 'yes') {
            resolve()
          } else {
            reject(new Error('User canceled the action'))
          }
        },
      })
    })
  }

  static async #delete ({ heramsEndpoint, heramsConfirm, heramsRedirect }) {
    // Configuration
    /**
     2. execute DELETE on endpoint
     3. check result
     4. Do redirect OR show error message
     */

    try {
      await this.#confirm(heramsConfirm)
      await this.fetchWithCsrf(heramsEndpoint, null, 'DELETE')
      window.location.assign(heramsRedirect)
      this.notifySuccess()
    } catch (error) {
      this.#notifyError(typeof error === 'string' ? error : error.message)
      console.error(error)
    }
  }

  #getCsrfToken () {
    return window.yii.getCsrfToken()
  }

  async notifySuccess (message = '', position = 'topRight') {
    return new Promise((resolve, reject) => {
      this.#iziToast.success({
        position,
        message,
        onClosed: () => resolve(),
      })
    })
  }

  #notifyError (message) {
    this.#iziToast.error({
      position: 'topRight',
      message,
    })
  }

  async fetchWithCsrf (uri, body = null, method = 'POST') {
    const response = await fetch(uri, {
      method,
      mode: 'cors',
      cache: 'no-cache',
      credentials: 'same-origin',
      headers: {
        'X-CSRF-Token': this.#getCsrfToken(),
        Accept: 'application/json;indent=2',
        'Content-Type': 'application/json',
      },
      body: (body !== null && typeof body === 'object') ? JSON.stringify(body) : body,
      redirect: 'error',
      referrer: 'no-referrer',
    })

    if (response.status === 422) {
      const json = await response.json()
      throw new ValidationError(json)
    }

    if (!response.ok) {
      throw new Error(`Request failed with code (${response.status}): ${response.statusText}`)
    }
    return response.json()
  }

  static async createElement (uri, plainObject, successMessage) {
    const response = await this.fetchWithCsrf(uri, plainObject)
    if (response.ok) {
      Herams.notifySuccess(successMessage ?? 'Element created')
    } else {
      try {
        const body = await response.json()
        Herams.#notifyError(body.message)
      } catch (error) {
        Herams.#notifyError("Server couldn't handle command: " + response.statusText)
      }
    }
  }
}
export default Herams
export { ValidationError }
