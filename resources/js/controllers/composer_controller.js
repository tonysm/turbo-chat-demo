import { Controller } from "@hotwired/stimulus"

// Connects to data-controller="composer"
export default class extends Controller {
    static targets = ['form', 'messageBox']

    reset() {
        this.formTarget.reset()
        this.messageBoxTarget.focus()
    }

    submitByKeyboard(event) {
        if (event.shiftKey) return

        event.preventDefault()
        this.formTarget.requestSubmit()
    }
}
