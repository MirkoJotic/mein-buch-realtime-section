/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */


import 'babel-polyfill'
import Vue from 'vue'


Vue.config.debug = true;
Vue.use(require('vue-resource'))
/* SHARED DATA STORE - SOURCE OF ALL TRUTH */
import store from './store/store';

Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr('content')

Vue.component('create-task', require('./components/createTask.vue'))
Vue.component('task-list', require('./components/TaskList.vue'))
Vue.component('sidebar', require('./components/Sidebar.vue'))
Vue.component('conversations-list', require('./components/ConversationsList.vue'))
Vue.component('conversations-list-heading', require('./components/ConversationsListHeading.vue'))
Vue.component('conversations-list-body', require('./components/ConversationsListBody.vue'))
Vue.component('conversations-list-body-item', require('./components/ConversationsListBodyItem.vue'))

Vue.component('active-conversation', require('./components/ActiveConversation.vue'))
Vue.component('active-conversation-heading', require('./components/ActiveConversationHeading.vue'))
Vue.component('active-conversation-body', require('./components/ActiveConversationBody.vue'))
Vue.component('active-conversation-form', require('./components/ActiveConversationForm.vue'))
Vue.component('active-conversation-add-people', require('./components/ActiveConversationAddPeople.vue'))


const app = new Vue({
    el: '#app',
    store
});
