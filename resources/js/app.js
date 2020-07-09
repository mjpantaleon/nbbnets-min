
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

Vue.mixin({
    methods : {
        printBloodBagLabel(component){
        //    let url =  'http://'+window.location.host+window.location.pathname+'label?facility_cd='+facility_cd+'&donation_id='+donation_id+'&component_cd='+component_cd;
            // let url =  'http://'+window.location.host+window.location.pathname+'/preview?data='+component;
            let url =  'http://'+window.location.host+'/preview?data='+component;
           
            let w = window.open(url,'winname','directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=375,height=270');
            w.onload = () => {
                w.print();

                var mediaQueryList = w.matchMedia('print');
                mediaQueryList.addListener(function(mql) {

                    console.log(mql)

                    if (mql.matches) {
                        console.log('before print dialog open');
                    } else {
                        console.log('after print dialog closed');
                    }
                });

                // setTimeout(function(){w.close();}, 10000);
                // w.close();
            };
        },
    }
});

// avoid illegal access of routes
router.beforeEach((to, from , next) => {
    axios
        .get('/get-user')
        .then(response => {

            if(response.data.status){
                next()
            } else{
                if (to.path !== '/') {
                    next('/')
                } else{
                    next()
                }
            }

        })
});

const app = new Vue({
    el: '#app',
    store,
    router: router,
});
