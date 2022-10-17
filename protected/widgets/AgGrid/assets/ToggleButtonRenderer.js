
// eslint-disable-next-line no-unused-vars
class ToggleButtonRenderer {

  #changing = false

  /** @type boolean */
  get state () {
    return this.eGui.firstChild.style.display !== 'none'
  }

  set state (value) {
    if (!value) {
      this.eGui.firstChild.style.display = 'none'
      this.eGui.lastChild.style.display = ''
    } else {
      this.eGui.firstChild.style.display = ''
      this.eGui.lastChild.style.display = 'none'
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
    console.warn(this.params.value)
    this.eGui.innerHTML = `
        <span class="iconify" data-icon="${this.params.onIcon ?? 'mdi-star'}" style="display: ${this.params.value ? '' : 'none'}"></span>
        <span class="iconify" data-icon="${this.params.offIcon ?? 'mdi-star-outline'}" style="display: ${this.params.value ? 'none' : ''}"></span>
    `.trim()

    this.btnClickedHandler = this.btnClickedHandler.bind(this)
    this.eGui.addEventListener('click', this.btnClickedHandler)
  }

  getGui () {
    return this.eGui
  }

  refresh (params) {
    this.state = params.value
    return true
  }

  async btnClickedHandler (event) {
    if (this.#changing) return
    this.#changing = true
    const animation = ['animate__animated', 'animate__heartBeat', 'animate__infinite'];
    this.eGui.classList.add(...animation)
    const url = new URL(this.params.endpoint)
    url.searchParams.append(this.params.paramName ?? 'target_id', this.params.data[this.params.idField ?? 'id'])

    // request type depends on state. use delete request if current state is true, use put if it is false.
    try {
      const response = await fetch(url.toString(), {
        method: this.state ? 'delete' : 'put',
      })

      if (response.ok) {
        this.params.setValue(!this.state)
        // this.params.node.data[this.params.column.colId] = !this.state
        // this.params.node.setDataValue(this.params.column.colId, !this.state)
        this.params.api.applyTransaction({
          update: [this.params.node.data]
          }

        );

      }
    } catch (error) {
      console.error(error)
    }
    this.eGui.classList.remove(...animation)
    this.#changing = false
  }

  destroy () {
    this.eGui.removeEventListener('click', this.btnClickedHandler)
  }
}
