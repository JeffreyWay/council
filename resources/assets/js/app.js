/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component("flash", require("./components/Flash.vue"));
Vue.component("paginator", require("./components/Paginator.vue"));
Vue.component(
    "user-notifications",
    require("./components/UserNotifications.vue")
);
Vue.component("avatar-form", require("./components/AvatarForm.vue"));
Vue.component("activities", require("./components/Activities"));
Vue.component("activity-layout", require("./components/ActivityLayout"));
Vue.component("activity-favorite", require("./components/ActivityFavorite"));
Vue.component("activity-reply", require("./components/ActivityReply"));
Vue.component("activity-thread", require("./components/ActivityThread"));
Vue.component("wysiwyg", require("./components/Wysiwyg.vue"));
Vue.component("dropdown", require("./components/Dropdown.vue"));
Vue.component("channel-dropdown", require("./components/ChannelDropdown.vue"));
Vue.component("logout-button", require("./components/LogoutButton"));
Vue.component("login", require("./components/Login"));
Vue.component("register", require("./components/Register"));
Vue.component("highlight", require("./components/Highlight"));
Vue.component('users-online', require('./components/UsersOnline.vue'));
Vue.component('viewing-thread', require('./components/ViewingThread.vue'));


Vue.component("thread-view", require("./pages/Thread.vue"));

const app = new Vue({
    el: "#app",

    data: {
        searching: false
    },

    methods: {
        search() {
            this.searching = true;

            this.$nextTick(() => {
                this.$refs.search.focus();
            });
        }
    }
});
