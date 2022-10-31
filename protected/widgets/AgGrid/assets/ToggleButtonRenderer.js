
// eslint-disable-next-line no-unused-vars
class ToggleButtonRenderer {
  #changing = false

  #uri = null

  /** @type string|null|undefined */
  get uri () {
    return this.#uri
  }

  set uri (value) {
    if (value === undefined) {
      value = null
    }
    this.#uri = value
    if (value !== null) {
      this.eGui.firstChild.style.display = ''
      this.eGui.lastChild.style.display = 'none'
    } else {
      this.eGui.firstChild.style.display = 'none'
      this.eGui.lastChild.style.display = ''
    }
  }

  init (params) {
    this.params = params

    this.eGui = document.createElement('button')
    for (const [prop, value] of Object.entries({
      border: 'none',
      backgroundColor: 'transparent',
      fontSize: '2em',
      // display: 'block',
      // margin: 'auto',
      // width: '100px'
    })) {
      this.eGui.style[prop] = value
    }
    this.eGui.innerHTML = `
        <span class="iconify" data-icon="${this.params.onIcon ?? 'mdi-star'}" style="display: none"></span>
        <span class="iconify" data-icon="${this.params.offIcon ?? 'mdi-star-outline'}" style="display: none"></span>
    `.trim()

    this.btnClickedHandler = this.btnClickedHandler.bind(this)
    this.eGui.addEventListener('click', this.btnClickedHandler)
    this.uri = params.value
  }

  getGui () {
    return this.eGui
  }

  refresh (params) {
    this.uri = params.value
    return true
  }

  async btnClickedHandler (event) {
    if (this.#changing) return
    this.#changing = true
    const animation = ['animate__animated', 'animate__heartBeat', 'animate__infinite']
    this.eGui.classList.add(...animation)
    const url = new URL(this.params.endpoint)

    // request type depends on state. use delete request if current state is true, use put if it is false.
    if (this.uri === null) {
      // put
      try {
        const paramName = this.params.paramName ?? 'target_id'
        const postData = this.params.postData
        postData[paramName] = this.params.data[this.params.idField ?? 'id']
        postData[this.params.paramName] = ''
        const response = await fetch(url.toString(), {
          method: 'post',
        })

        if (response.ok) {
          this.params.setValue(!this.checked)
          // this.params.node.data[this.params.column.colId] = !this.checked
          // this.params.node.setDataValue(this.params.column.colId, !this.checked)
          this.params.api.applyTransaction({
                update: [this.params.node.data],
              },

          )
        }
      } catch (error) {
        console.error(error)
      }
    } else {
      // delete
      const response = await fetch(this.uri, {
        method: 'delete',
      })
      if (response.ok) {
        this.params.setValue(null)
        this.params.api.applyTransaction({
          update: [this.params.node.data],
        })
      }
    }

    this.eGui.classList.remove(...animation)
    this.#changing = false
  }

  destroy () {
    this.eGui.removeEventListener('click', this.btnClickedHandler)
  }
}
