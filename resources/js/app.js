import Alpine from 'alpinejs'
import focus from '@alpinejs/focus'
import persist from '@alpinejs/persist'

Alpine.plugin(focus)
Alpine.plugin(persist)

// Global Alpine data
Alpine.store('darkMode', {
    on: Alpine.$persist(false).as('darkMode'),
    toggle() {
        this.on = !this.on
        document.documentElement.classList.toggle('dark', this.on)
    },
    init() {
        document.documentElement.classList.toggle('dark', this.on)
    }
})

window.Alpine = Alpine
Alpine.start()