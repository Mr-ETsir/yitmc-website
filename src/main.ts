import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import './styles/tokens.css'
import './styles/fonts.css'
import './styles/blocks.css'
import './styles/animations.css'

const app = createApp(App)
app.use(router)
app.mount('#app')
