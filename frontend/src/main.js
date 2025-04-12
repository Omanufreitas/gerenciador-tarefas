import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import './style.css'
import './bootstrap.js'

// IMPORTA OS ÍCONES
import { PencilIcon, TrashIcon } from '@heroicons/vue/24/solid'

const app = createApp(App)

app.component('PencilIcon', PencilIcon)
app.component('TrashIcon', TrashIcon)

app.use(router).mount('#app')
