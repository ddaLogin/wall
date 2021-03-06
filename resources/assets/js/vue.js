/**
 * Vue is a modern JavaScript library for building interactive web interfaces
 * using reactive data binding and reusable components. Vue's API is clean
 * and simple, leaving you to focus on building your next great project.
 */

window.Vue = require('vue');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common = {
    'X-CSRF-TOKEN': window.Laravel.csrfToken,
    'X-Requested-With': 'XMLHttpRequest'
};

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));
Vue.component('search', require('./components/Search.vue'));
Vue.component('like', require('./components/Like.vue'));
Vue.component('subscriptionButton', require('./components/SubscriptionButton.vue'));
Vue.component('subscriptionsTable', require('./components/SubscriptionsTable.vue'));
Vue.component('avatarSetter', require('./components/AvatarSetter.vue'));
Vue.component('notificator', require('./components/Notificator.vue'));
Vue.component('conferenceContainer', require('./components/ConferenceContainer.vue'));


//special mix object to extend default vue instance
if (!window.hasOwnProperty('mix')) {
    window.mix = {};
}

const app = new Vue({
    el: '#app',
    mixins: [window.mix]
});
