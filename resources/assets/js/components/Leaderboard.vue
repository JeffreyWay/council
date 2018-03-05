<template>
    <div>
        <div v-for="(user, key) in leaderboard" :key="user.id" class="my-10 p-4 rounded border border-grey-light flex justify-between">
            <div class="flex">
                <img :src="user.avatar_path" :alt="user.username" :class="avatarClasses(user.avatar_path)" class="w-16 h-16 rounded-full mr-3">
                <div>
                    <a :href="userProfileLink(user.username)" class="inline mb-4 text-xl text-blue">{{ user.username }}</a>
                    <span class="inline px-2 bg-green rounded font-semibold text-white">{{ user.reputation }} XP</span>
                </div>
            </div>
            <span class="text-5xl text-grey-light">{{ key+1 }}</span>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Leaderboard',

    data() {
        return {
            leaderboard: {}
        };
    },

    created() {
        this.fetch();
    },

    methods: {
        fetch() {
            window.axios.get('api/leaderboard').then(this.refresh);
        },

        refresh({ data }) {
            this.leaderboard = data.leaderboard;
        },

        userProfileLink(username) {
            return `/profiles/${username}`;
        },

        avatarClasses(avatarPath) {
            return {
                'bg-blue-darker p-2': avatarPath.endsWith('default.svg')
            }
        }
    }
};
</script>