<template>
    <div>
        <div ref="timeline" class="mt-2">&nbsp;</div>

        <div class="timeline relative w-full max-w-full border-l-4 border-grey-light">
            <div v-for="(activity, index) in items" :key="activity.id">
                <div class="entry">
                    <activity-favorite :activity="activity" v-if="activity.type === 'created_favorite'"/>
                    <activity-reply :activity="activity" v-if="activity.type === 'created_reply'"/>
                    <activity-thread :activity="activity" v-if="activity.type === 'created_thread'"/>
                </div>
            </div>
        </div>

        <paginator class="list-reset py-2 mb-4" :data-set="dataSet" @changed="fetch"/>
    </div>
</template>

<script>
export default {
    name: 'Activities',

    props: {
        user: {
            type: Object,
            required: true
        }
    },

    data() {
        return {
            dataSet: false,
            items: false
        };
    },

    created() {
        this.fetch();
    },

    methods: {
        fetch(page) {
            axios.get(this.url(page)).then(this.refresh);
        },

        url(page) {
            let url = `/profiles/${this.user.username}/activity`;

            return page ? `${url}?page=${page}` : `${url}?page=1`;
        },

        refresh({ data }) {
            this.dataSet = data.activities;
            this.items = data.activities.data;

            this.$refs.timeline.scrollIntoView();
        }
    }
};
</script>
