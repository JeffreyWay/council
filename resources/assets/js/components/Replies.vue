<template>
    <div class="flex" style="margin-left: 56px">
        <div>
            <div v-for="(reply, index) in items" :key="reply.id">
                <reply :reply="reply" @deleted="remove(index)"></reply>
            </div>

            <paginator :dataSet="dataSet" @changed="fetch"></paginator>

            <p v-if="$parent.locked" class="mt-4 text-sm text-grey-dark text-center">
                This thread has been locked. No more replies are allowed.
            </p>

            <new-reply @created="add" v-else></new-reply>
        </div>
    </div>
</template>

<script>
import Reply from "./Reply.vue";
import NewReply from "./NewReply.vue";
import collection from "../mixins/collection";

export default {
    components: { Reply, NewReply },

    mixins: [collection],

    data() {
        return { dataSet: false };
    },

    created() {
        this.fetch();

        if (typeof window.Echo !== 'undefined') {
            window.Echo.join(`forum.${window.channelName}`)
              .listen('ThreadReceivedNewReply', ({reply}) => {
                  if (reply.user_id === window.App.user.id) {
                      return;
                  }
                  this.add(reply)
                  window.flash('A reply has just been posted.')
              });
        }
    },

    methods: {
        fetch(page) {
            axios.get(this.url(page)).then(this.refresh);
        },

        url(page) {
            if (!page) {
                let query = location.search.match(/page=(\d+)/);

                page = query ? query[1] : 1;
            }

            return `${location.pathname}/replies?page=${page}`;
        },

        refresh({ data }) {
            this.dataSet = data;
            this.items = data.data;

            window.scrollTo(0, 0);
        }
    }
};
</script>
