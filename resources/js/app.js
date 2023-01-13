
require('./bootstrap');

window.Vue = require('vue');
window.moment = require('moment');
window.feather = require('feather-icons');
window.axios.defaults.baseURL = '/api/';

import VueRouter from 'vue-router';
import routes from './routes';
import _ from 'lodash';

Vue.use(VueRouter);
// Vue.component('home', require('./components/Home.vue').default);

const app = new Vue({
    el: '#app',
    router: new VueRouter(routes)
});
