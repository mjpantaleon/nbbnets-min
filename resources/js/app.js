
require('./bootstrap');

import Vue from 'vue';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
import VueRouter from 'vue-router';

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

import {routes} from './routes';

import {ac_routes} from './access_routes.js';

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
                setTimeout(function(){w.close();}, 3000);
                // w.close();
            };
        },

        // printIssuanceForm(id){
        //     let url =  'http://'+window.location.host+'/preview?data='+id;
        //     let w = window.open(url,'winname','directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=595,height=842');
        //     w.onload = () => {
        //         w.print();
        //         setTimeout(function(){w.close();}, 3000);
        //         // w.close();
        //     };
        // }
    }
});

// avoid illegal access of routes
router.beforeEach((to, from , next) => {

    axios
        .get('/get-user')
        .then(response => {

            if(response.data.status){

                var ual = [];

                if(response.data.ulevel == 3){
                    ual = ac_routes.BCM
                } else if(response.data.ulevel == 4){
                    ual = ac_routes.MT
                } else if(response.data.ulevel == 7){
                    ual = ac_routes.HBBM
                }

                // console.log(to.name)
                // console.log(ual.indexOf('blood-typings'))

                if(ual.length){
                    if(ual.indexOf(to.name) === -1){
                        next('/unauthorized')
                    } else{
                        next()                         
                    }
                } else{
                    next()
                }

                // next()

                
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
