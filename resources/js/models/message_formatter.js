export default class MessageFormatter {
    #userId
    #options

    constructor(userId, options) {
        this.#userId = userId;
        this.#options = options;
    }

    format(element) {
        this.#setMeClass(element);
    }

    #setMeClass(element) {
        if (this.#userId === element.dataset.userId) {
            element.classList.add(...this.#options.me)
        }
    }
}
