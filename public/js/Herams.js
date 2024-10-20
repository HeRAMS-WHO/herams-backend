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
    try {
      iziToast.show({
        message: heramsConfirm,
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
            const response = this.delete(heramsEndpoint, heramsRedirect)

            // const response = await this.delete(heramsEndpoint, heramsRedirect);
          } else {

          }
        },
      })
    } catch (error) {
      console.error(error)
    }
  }

  static async delete (uri, redirect) {
    $.ajax({
      method: 'POST',
      url: uri,
      mode: 'cors',
      cache: 'no-cache',
      credentials: 'same-origin',
      headers: {
        'X-CSRF-Token': window.yii.getCsrfToken(),
        Accept: 'application/json;indent=2',
        'Accept-Language': document.documentElement.lang ?? 'en',
        'Content-Type': 'application/json',
      },
      success: function (data) {

        if (data !== true){
          //matches por delete uri for eachone in project, role, user, and userrole, with a regex which includes id
          // in this format resource/id/delete
          const matches = [
            {
              'regex': 'project/[0-9]+/delete',
              'message': 'This project is not empty, and cannot be deleted. Please make sure the project is empty before deleting it',
            },
            {
                'regex': 'roles/[0-9]+/delete',
                'message': 'This role is not empty, and cannot be deleted. Please make sure the role is empty before deleting it',
            }
          ]
          //check where the uri matches
          let message = '';
          for (let i = 0; i < matches.length; i++) {
            const regex = new RegExp(matches[i].regex); // Convert string to regular expression
            if (regex.test(uri)) { // Use .test() to check for a match
              message = matches[i].message;
              break;
            }
          }
          iziToast.show({
            message: message || 'An error occurred',
            overlay: true,
            overlayClose: true,
            color: 'red',
            closeOnEscape: true,
            position: 'center',

          })
        }
        else {
          window.location.assign(redirect)
        }
      },
      error: function (data) {
        console.log('An error occurred.')
      },
    })
    // const response = await fetch(uri, {
    //   method: 'POST',
    //   mode: 'cors',
    //   cache: 'no-cache',
    //   credentials: 'same-origin',
    //   headers: {
    //     'X-CSRF-Token': window.yii.getCsrfToken(),
    //     Accept: 'application/json;indent=2',
    //     'Accept-Language': document.documentElement.lang ?? 'en',
    //     'Content-Type': 'application/json',
    //   },
    //   redirect: 'error',
    //   referrer: 'no-referrer',
    // })
    // if (response) {
    //     window.location.assign(redirect);
    // }
  }

  #getCsrfToken () {
    return window.yii.getCsrfToken()
  }

  async notifySuccess (message = '', position = 'topRight', timeout = '500') {
    return new Promise((resolve, reject) => {
      this.#iziToast.success({
        position,
        message,
        timeout,
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

  async deleteWithCsrf (uri) {
    const response = await fetch(uri, {
      method: 'delete',
      mode: 'cors',
      cache: 'no-cache',
      credentials: 'same-origin',
      headers: {
        'X-CSRF-Token': this.#getCsrfToken(),
      },
      redirect: 'error',
      referrer: 'no-referrer',
    })

    if (!response.ok) {
      throw new Error(`Delete failed with code (${response.status}): ${response.statusText}`)
    }
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
        'Accept-Language': document.documentElement.lang ?? 'en',
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
    if (response.status === 204) {
      return null
    }

    if (!response.ok) {
      if (response.headers.get('Content-Type').startsWith('application/json')) {
        const content = await response.json()
        throw new Error(`Request failed with code (${response.status}): ${response.statusText}, content: ${content}`)
      } else {
        throw new Error(`Request failed with code (${response.status}): ${response.statusText}`)
      }
    }
    return response.json()
  }

  /**
   * Sends a POST request to the collection URI. Returns the Location of the created entity
   * @param uri
   * @param body
   * @returns {Promise<void>}
   */
  async createInCollectionWithCsrf (uri, body) {
    const response = await fetch(uri, {
      method: 'POST',
      mode: 'cors',
      cache: 'no-cache',
      credentials: 'same-origin',
      headers: {
        'X-CSRF-Token': this.#getCsrfToken(),
        Accept: 'application/json;indent=2',
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(body),
      redirect: 'error',
      referrer: 'no-referrer',
    })

    if (response.status === 422) {
      const json = await response.json()
      throw new ValidationError(json)
    }
    if (response.status === 204 || response.status === 303) {
      return response.headers.get('Location')
    }

    if (!response.ok) {
      if (response.headers.get('Content-Type').startsWith('application/json')) {
        const content = await response.json()
        throw new Error(`Request failed with code (${response.status}): ${response.statusText}, content: ${content}`)
      } else {
        throw new Error(`Request failed with code (${response.status}): ${response.statusText}`)
      }
    }
    throw new Error(`Expected status code 204 or 303, got ${response.status}`)
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
