require('./bootstrap');

import Vue from 'vue';
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import lang from 'element-ui/lib/locale/lang/en';
import locale from 'element-ui/lib/locale';

locale.use(lang);

window.Vue = require('vue').default;

Vue.use(ElementUI);

let loader;

Vue.prototype.$showLoader = function() {
    return this.$loading({
        lock: true,
        text: "Loading...",
        spinner: "el-icon-loading",
        background: "rgba(0, 0, 0, 0.7)"
    });
};

Vue.prototype.$hideLoader = function() {
    if (loader) {
        loader.close();
    }
};

Vue.component('login-form-component', require('./components/auth/LoginComponent.vue').default);
Vue.component('register-form-component', require('./components/auth/RegisterComponent.vue').default);
Vue.component('news-table-component', require('./components/news/NewsTableComponent.vue').default);


const app = new Vue({
    el: '#vue-app',
});
