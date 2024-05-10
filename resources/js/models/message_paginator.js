const MAX_MESSAGES = 20;

class ScrollTracker {
    #container
    #callback
    #intersectionObserver
    #mutationObserver
    #firstChildWasHidden

    constructor(container, callback) {
        this.#container = container
        this.#callback = callback

        this.#intersectionObserver = new IntersectionObserver(this.#handleIntersection.bind(this), { root: container })

        this.#mutationObserver = new MutationObserver(this.#childrenChanged.bind(this))
        this.#mutationObserver.observe(container, { childList: true })
    }

    connect() {
        this.#childrenChanged()
    }

    disconnect() {
        this.#intersectionObserver.disconnect()
    }

    // private

    #handleIntersection(entries) {
        for (const entry of entries) {
            const isFirst = entry.target === this.#container.firstElementChild
            const significantReveal = (isFirst && this.#firstChildWasHidden) || !isFirst

            if (entry.isIntersecting) {
                if (significantReveal) {
                    this.#callback(entry.target)
                }
            } else {
                if (isFirst) {
                    this.#firstChildWasHidden = true
                }
            }
        }
    }

    #childrenChanged() {
        this.disconnect()

        if (this.#container.firstElementChild) {
            this.#firstChildWasHidden = false

            this.#intersectionObserver.observe(this.#container.firstElementChild)
            this.#intersectionObserver.observe(this.#container.lastElementChild)
        }
    }
}

export default class MessagePaginator {
    #container
    #url
    #formatter
    #scrollTracker
    #upToDate = false

    constructor(container, url, formatter) {
        this.#container = container
        this.#url = url
        this.#formatter = formatter

        this.#scrollTracker = new ScrollTracker(container, this.#messageBecameVisible.bind(this))
    }

    monitor() {
        this.#scrollTracker.connect()
    }

    disconnect() {
        this.#scrollTracker.disconnect()
    }

    get upToDate() {
        return this.#upToDate
    }

    set upToDate(value) {
        this.#upToDate = value
    }

    async trimMessages(top) {
        const overage = this.#container.children.length - MAX_MESSAGES

        if (overage <= MAX_MESSAGES) return;

        trimChildren(overage, this.#container, top)

        if (! top) {
            this.upToDate = false
        }
    }

    // private

    #messageBecameVisible(element) {
        const messageId = element.dataset.messageId
        const firstMessage = element === this.#container.firstElementChild
        const lastMessage = element === this.#container.lastElementChild

        if (! messageId) return

        if (firstMessage) {
            this.#addPage({ after: messageId }, true)
        } else if (lastMessage && ! this.upToDate) {
            this.#addPage({ before: messageId }, false)
        }
    }

    async #addPage(params, top) {
        const resp = await this.#fetchPage(params)

        if (resp.status === 204 && !top) {
            this.upToDate = true
        }

        if (resp.status === 200) {
            const page = await this.#formatPage(resp)

            const lastNewElement = page.lastElementChild

            keepScroll(this.#container, top, () => {
                insertHTMLFragment(page, this.#container, top)

                if (top && lastNewElement?.nextElementSibling) {
                    this.#formatter.format(lastNewElement.nextElementSibling)
                }
            })

            this.trimMessages(! top)
        }
    }

    async #fetchPage(params) {
        const url = new URL(this.#url)

        for (const param in params) {
            url.searchParams.set(param, params[param])
        }

        return await fetch(url, {
            headers: {
                'X-CSRF-Token': document.head.querySelector('meta[name=csrf-token]').content,
            },
        })
    }

    async #formatPage(response) {
        const text = await response.text()
        const fragment = parseHTMLFragment(text)

        for (const message of fragment.querySelectorAll('.message')) {
            this.#formatter.format(message)
            message.classList.add('border', 'border-red-500')
        }

        return fragment
    }
}

function trimChildren(count, container, top) {
    const children = Array.from(container.children)
    const elements = top ? children.slice(0, count) : children.slice(-count)

    keepScroll(container, top, function () {
        for (const el of elements) {
            el.remove()
        }
    })
}

async function keepScroll(container, top, fn) {
    pauseInertiaScroll(container)

    const scrollTop = container.scrollTop
    const scrollHeight = container.scrollHeight

    await fn()

    if (top) {
        container.scrollTop = scrollTop + (container.scrollHeight - scrollHeight)
    } else {
        container.scrollTop = scrollTop
    }
}

function pauseInertiaScroll(container) {
    container.style.overflow = "hidden"

    requestAnimationFrame(() => {
        container.style.overflow = ""
    })
}

function parseHTMLFragment(html) {
    const template = document.createElement("template")
    template.innerHTML = html
    return template.content
}

export function insertHTMLFragment(fragment, container, top) {
    if (top) {
      container.prepend(fragment)
    } else {
      container.append(fragment)
    }
}
