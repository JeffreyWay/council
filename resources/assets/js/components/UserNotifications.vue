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

        computed: {
            endpoint() {
                return `/profiles/${window.App.user.name}/notifications`;
            }
        },

        methods: {
            fetchNotifications() {
                axios.get(this.endpoint)
                    .then(response => this.notifications = response.data);
            },

            markAsRead(notification) {
                axios.delete(`${this.endpoint}/${notification.id}`)
                    .then(({data}) => {
                        this.fetchNotifications();

                        document.location.replace(data.link);
                    });
            }
        }
    }
</script>
