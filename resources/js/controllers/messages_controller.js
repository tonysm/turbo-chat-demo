import { Controller } from "@hotwired/stimulus"
import MessageFormatter from "models/message_formatter"
import MessagePaginator from "models/message_paginator"

// Connects to data-controller="messages"
export default class extends Controller {
    static values = {
        userId: String,
        pageUrl: String,
    }

    static targets = ["messages"]

    static classes = ["me"]

    #formatter
    #paginator

    initialize() {
        this.#formatter = new MessageFormatter(Current.user.id, {
            me: this.meClasses,
        })
    }

    connect() {
        this.#paginator = new MessagePaginator(this.element, this.pageUrlValue, this.#formatter);
        this.#paginator.monitor()
    }

    disconnect() {
        this.#paginator.disconnect()
    }

    messageTargetConnected(element) {
        this.#formatter.format(element)
    }
}
