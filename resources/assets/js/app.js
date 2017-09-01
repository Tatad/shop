
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import Axios from 'axios';

Vue.component('example', require('./components/Example.vue'));
Vue.component('shop', require('./components/Shop.vue'));
Vue.component('employee', require('./components/Employee.vue'));
Vue.component('post', require('./components/Post.vue'));
//Vue.component('search', require('./components/Search.vue'));
Vue.component('Typeahead', require('./components/Search.vue'));
Vue.prototype.$http = Axios;

// var MyComponent = require('./components/Search.vue')

// Vue.component('vueTypeahead', MyComponent);

const app = new Vue({
    el: '#app',
    data: function() {
        return{
            label:'',
            value: '',
            value1: '',
            myTemplate: '<div><h3>{{name}}</h3><h4>{{address}}</h4></div>',
            localValues: ['Dhaka', 'Rangpur', 'Rajshahi', 'Sylhet', 'Khulna']
        }
    },
    methods: {
        done: function(data) {
            console.log(data);
        }
    }
});
