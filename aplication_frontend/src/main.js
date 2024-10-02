import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import store from './store';
import '@fortawesome/fontawesome-free/css/all.min.css';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap/dist/js/bootstrap'; // Esto debería estar antes del montaje

// Inicialización de la aplicación Vue 3
createApp(App)
  .use(store)  // Asegúrate de usar Vuex
  .use(router)  // Asegúrate de usar el router
  .mount('#app');

// No necesitas `Vue.config.productionTip = false;` ni `new Vue({...})` en Vue 3
