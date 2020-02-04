import App from './App.vue';
import '../node_modules/vuetify/dist/vuetify.min.css';
import '@mdi/font/css/materialdesignicons.css'
import Vuetify from 'vuetify'
import swal from 'sweetalert';
import VueRouter from 'vue-router';
import axios from 'axios';
import VueAxios from 'vue-axios';
import router from './router.js';
require('./bootstrap');
window.Vue = require('vue');



// DECLARANDO COMPONENTES
Vue.component('picpixreader', require('./components/PicPixReader.vue').default);
Vue.component('images-all', require('./components/ImagesAll.vue').default);
// FIN COMPONENTES

/* PLUGINS */
Vue.use(Vuetify);
// Vue.use(axios,VueAxios);
/* FIN DE PLUGINS */

new Vue({
    vuetify : new Vuetify({
        icons: {
            iconfont: 'mdi', // default - only for display purposes
        }
    }),
    router,
    render: h=>h(App)
}).$mount('#app');