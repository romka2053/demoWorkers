/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');


window.Vue = require('vue').default;
import router from './router/router';

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('my-app', require('./app.vue').default);

import '@mdi/font/css/materialdesignicons.css'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'

Vue.use(Vuetify)

const opts = {

    icons:{
        iconfont:'mdi'

    }
}


const app = new Vue({
    el: '#app',
    router,
    vuetify: new Vuetify(opts)
});
