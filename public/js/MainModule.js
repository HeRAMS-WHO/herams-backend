import Herams from './Herams.js'

if (!window.Herams) {
  console.log('Initializing herams in the document')
  window.Herams = new Herams(document, window.iziToast)

  // Trigger all registered callbacks.
  const domContentLoaded = new Promise((resolve, reject) => {
    if (document.readyState === "complete" || document.readyState === "loaded") {
      resolve()
    } else {
      document.addEventListener('DOMContentLoaded', () => {
        resolve()
      })
    }
  })
  await domContentLoaded

  if (typeof window.__herams_init_callbacks !== 'undefined') {
    const callbacks = window.__herams_init_callbacks;
    (async () => {
      for (let i = 0; i < callbacks.length; i++) {
        console.debug('Executing callback')
        await callbacks[i]()
      }
    })()
  }

  // After initialization callbacks are called immediately
  const safeExecute = (callback) => {
    try {
      console.log('Running callback immediately', callback)
      callback()
    } catch (e) {
      console.error(e)
    }
  }

  window.__herams_init_callbacks = {
    unshift: safeExecute,
    push: safeExecute,
  }
}
