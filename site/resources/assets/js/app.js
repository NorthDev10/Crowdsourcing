
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import Snotify                      from 'vue-snotify' // всплывающие сообщения (toast)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('search-input', require('./components/search-input/SearchInput.vue'));
Vue.use(Snotify);

import project from './components/Project/ProjectEdit.vue';
import myProfileEdit from './components/MyProfile/MyProfileEdit.vue';

const app = new Vue({
    el: '#app',
    components: {
        project,
        myProfileEdit,
    },
});
