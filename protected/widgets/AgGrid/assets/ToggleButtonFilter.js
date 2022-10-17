// eslint-disable-next-line no-unused-vars
class ToggleButtonFilter {
    #trueOption
    #falseOption

    #valueGetter
    init(params) {
        this.eGui = document.createElement('div');
        this.eGui.innerHTML = `
<div class="ag-filter-wrapper">
<div class="ag-simple-filter-body-wrapper">
<select multiple class="ag-filter-select">
        
    <option value="1">Yes</option>
    <option value="0">No</option>
</select>
</div>
</div>`

        const input = this.eGui.querySelector('select')
        input.addEventListener('change', params.filterChangedCallback)
        this.#trueOption = input.querySelector('option[value="1"]');
        this.#falseOption = input.querySelector('option[value="0"]');

        this.#valueGetter = params.valueGetter
    }

    getGui() {
        return this.eGui;
    }

    doesFilterPass(params) {
        return this.#valueGetter({node: params.node}) ? this.#trueOption.selected : this.#falseOption.selected;
    }

    isFilterActive() {
        return !(this.#trueOption.selected && this.#falseOption.selected) && (this.#trueOption.selected || this.#falseOption.selected);
    }

    // this example isn't using getModel() and setModel(),
    // so safe to just leave these empty. don't do this in your code!!!
    getModel() {
        return [this.#trueOption.selected, this.#falseOption.selected]
    }

    setModel([trueOptionState, falseOptionState]) {
        this.#trueOption.selected = trueOptionState
        this.#falseOption.selected = falseOptionState
    }

    getModelAsString() {
        if (!this.isFilterActive()) {
            return ''
        }
        return this.#trueOption.selected ? 'Yes' : 'No'
    }
}
