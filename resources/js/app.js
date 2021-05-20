/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

/**
 * Set Axios to always put CSRF token from Laravel into requests.
 * https://laracasts.com/discuss/channels/general-discussion/how-can-add-csrf-token-in-axios-post
 * we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
	window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
	console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}


// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

/**
 * Integrate Element UI
 */

import ElementUI from 'element-ui';
//https://dev.to/sirtimbly/overriding-the-default-theme-in-element-ui-with-scss-20bl
// import 'element-ui/lib/theme-chalk/index.css';//LOSI: EZ NEM KELL

import locale from 'element-ui/lib/locale/lang/en.js';
Vue.use(ElementUI, {locale});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// HOME/WELCOME
Vue.component('home-base', require('./components/home/HomeBase.vue').default);

// AD-LIST
Vue.component('ad-list-base', require('./components/adList/AdListBase.vue').default);

// AD-DETAIL
Vue.component('ad-detail-base', require('./components/adDetails/AdDetailBase.vue').default);
