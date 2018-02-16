<template>
    <div @mouseover="active = true" @mouseout="active = false">
       <div class="rounded-full bg-blue-darkest w-10 h-10 flex items-center justify-center mr-4 cursor-pointer relative z-10">
            <!-- "New Notifications Available" bubble. -->
            <div class="rounded-full bg-red w-2 h-2 absolute pin-t pin-r mt-1" v-if="notifications.length"></div>

            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="20" viewBox="0 0 16 20" class="fill-current">
                <g fill="none" fill-rule="evenodd"  >
                    <path d="M-4-2h24v24H-4z"/>
                    <path fill="#FFF" d="M8 20c1.1 0 2-.9 2-2H6a2 2 0 0 0 2 2zm6-6V9c0-3.07-1.64-5.64-4.5-6.32V2C9.5 1.17 8.83.5 8 .5S6.5 1.17 6.5 2v.68C3.63 3.36 2 5.92 2 9v5l-2 2v1h16v-1l-2-2z"/>
                </g>
            </svg>
        </div>

        <div class="relative" v-show="active">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span class="glyphicon glyphicon-bell"></span>
            </a>

            <div class="bg-grey-light p-6 text-black absolute rounded"
                 style="border-top-right-radius: 28px 22px; width: 313px; top: -32px; right: 23px"
            >
                <h4 class="mb-4">Notifications</h4>

                <ul class="list-reset">
                    <li v-for="(notification, index) in notifications"
                        :key="notification.id"
                        :class="index === notifications.length - 1 ? '' : 'mb-4'"
                    >
                        <a :href="notification.data.link"
                           class="text-xs flex items-center pr-1 link"
                           @click.prevent="markAsRead(notification)"
                        >
                            <img :src="notification.data.notifier.avatar_path"
                                 :alt="notification.data.notifier.username"
                                 class="w-8 mr-3">

                            <span v-text="notification.data.message"></span>
                        </a>
                    </li>

                    <li v-if="! notifications.length" class="text-xs">You have zero notifications.</li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return { notifications: false, active: false }
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
