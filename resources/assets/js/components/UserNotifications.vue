<template>
    <li class="dropdown" v-if="notifications.length">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <span class="glyphicon glyphicon-bell"></span>
        </a>

        <ul class="dropdown-menu">
            <li v-for="notification in notifications">
                <a :href="notification.data.link"
                   v-text="notification.data.message"
                   @click="markAsRead(notification)"
                ></a>
            </li>
        </ul>
    </li>
</template>

<script>
    import requestService from '../services/requestService';

    export default {
        props: ['userName'],
        data() {
            return {notifications: []}
        },

        created() {
            requestService.get(`/profiles/${this.userName}/notifications`)
                .then(notifications => this.notifications = notifications);
        },

        methods: {
            markAsRead(notification) {
                requestService.delete(`/profiles/${this.userName}/notifications/${notification.id}`)
                    .then(() => this.removeFromList(notification));
            },
            removeFromList(notification) {
                this.notifications = this.notifications.filter((item) => {
                    return item.id !== notification.id;
                });
            }
        }
    }
</script>
