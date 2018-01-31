<template>
    <li class="dropdown" v-if="notifications.length">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <span class="glyphicon glyphicon-bell"></span>
        </a>

        <ul class="dropdown-menu">
            <li v-for="notification in notifications" :key="notification.id">
                <a :href="notification.data.link"
                   v-text="notification.data.message"
                   @click.prevent="markAsRead(notification)"
                ></a>
            </li>
        </ul>
    </li>
</template>

<script>
    export default {
        data() {
            return { notifications: false }
        },

        created() {
            this.fetchNotifications();
        },

        methods: {
            fetchNotifications() {
                axios.get('/profiles/' + window.App.user.name + '/notifications')
                  .then(response => this.notifications = response.data);
            },
            markAsRead(notification) {
                axios.delete('/profiles/' + window.App.user.name + '/notifications/' + notification.id)
                .then(response => {
                    this.fetchNotifications();
                    document.location.replace(response.data.link);
                });
            }
        }
    }
</script>
