
require('./bootstrap');

import Vue from 'vue';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
import VueRouter from 'vue-router';

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

import {routes} from './routes';

import {store} from './store.js'

// Use BootstrapVue
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
Vue.use(VueRouter)

Vue.use(require('vue-moment'));

Vue.component('main-app', require('./MainApp.vue').default);

const router = new VueRouter({
    mode: 'history',
    routes: routes
});

const app = new Vue({
    el: '#app',
    store,
    router: router,
});
