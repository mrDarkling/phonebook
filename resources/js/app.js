/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// Vue.js
import Vue from 'vue';

import Snotify, { SnotifyPosition } from 'vue-snotify'

import ViewUI from 'view-design';
import 'view-design/dist/styles/iview.css';
import locale from 'view-design/dist/locale/ru-RU';

//vue router
import VueRouter from 'vue-router'
Vue.use(VueRouter)

// Подключаю axios
import axios from 'axios'

Vue.use(ViewUI, { locale });

const options = {toast: {position: SnotifyPosition.rightTop}};
Vue.use(Snotify, options);

import PhoneBook from './components/PhoneBook';
import EditPhone from './components/EditPhone';

Vue.component('PhoneBook', PhoneBook);
Vue.component('EditPhone', EditPhone);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

let router = new VueRouter ({
    routes: [
        {
            path: '/',
            name: 'PhoneBook',
            component: PhoneBook,
        },
        {
            path: '/edit-phone/:phone_id',
            name: 'EditPhone',
            component: EditPhone,
            props: true,
        }
    ]
})

const app = new Vue({
    el: '#app',
    router
}).$mount('#app');
