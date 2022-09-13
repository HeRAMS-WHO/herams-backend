
class ToggleButtonRenderer {

    init(params) {
        this.params = params;

        this.eGui = document.createElement('button');
        this.eGui.innerHTML = 'Click me!';

        this.btnClickedHandler = this.btnClickedHandler.bind(this);
        this.eGui.addEventListener('click', this.btnClickedHandler);
    }

    getGui() {
        return this.eGui;
    }

    btnClickedHandler(event) {
        const url = new URL(this.params.endpoint)
        url.searchParams.append("target_id", this.params.value);
        alert("doing request to "  + url.toString())
    }

    destroy() {
        this.eGui.removeEventListener('click', this.btnClickedHandler);
    }
}
