import { Controller } from "@hotwired/stimulus"

// Connects to data-controller="messages"
export default class extends Controller {
    static values = {
        userId: String,
    }

    static targets = ["messages"]

    static classes = ["me"]

    messageTargetConnected(element) {
        this.#setMeClass(element)
    }

    #setMeClass(element) {
        if (this.userIdValue === element.dataset.userId) {
            element.classList.add(...this.meClasses)
        }
    }
}
