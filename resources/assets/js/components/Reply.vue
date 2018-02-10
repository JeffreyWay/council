<template>
    <div :id="'reply-'+id" class="border-b py-6" :class="isBest ? 'panel-success': 'panel-default'">
        <div class="flex">
            <img src="/images/avatars/default.png"
                 alt=""
                 width="36"
                 height="36"
                 class="mr-4">

            <div>
                <h5 class="flex-1 text-blue mb-2 font-normal">
                    <a class="text-blue font-bold" :href="'/profiles/' + reply.owner.name"
                        v-text="reply.owner.name">
                    </a> said <span v-text="ago"></span>
                </h5>


                <div class="mb-4">
                    <div v-if="editing">
                        <form @submit.prevent="update">
                            <div class="form-group">
                                <wysiwyg v-model="body"></wysiwyg>
                            </div>

                            <button type="submit" class="btn btn-xs btn-primary">Update</button>
                            <button class="btn btn-xs btn-link" @click="cancel" type="button">Cancel</button>
                        </form>
                    </div>

                    <div v-else>
                        <highlight :content="body"></highlight>
                    </div>
                </div>

                <div class="flex" v-if="authorize('owns', reply) || authorize('owns', reply.thread)">
                    <div v-if="authorize('owns', reply)">
                        <button class="text-blue" @click="editing = true" v-if="! editing">Edit</button>
                        <!-- <button class="btn btn-xs btn-danger mr-1" @click="destroy">Delete</button> -->
                    </div>

                    <button class="btn btn-xs btn-default ml-a" @click="markBestReply" v-if="authorize('owns', reply.thread)">Best Reply?</button>
                </div>

    <!--             <div v-if="signedIn">
                        <favorite :reply="reply"></favorite>
                    </div> -->
            </div>
        </div>
    </div>
</template>

<script>
    import Favorite from './Favorite.vue';
    import Highlight from './Highlight.vue';
    import moment from 'moment';

    export default {
        props: ['reply'],

        components: { Favorite, Highlight },

        data() {
            return {
                editing: false,
                id: this.reply.id,
                body: this.reply.body,
                isBest: this.reply.isBest,
            };
        },

        computed: {
            ago() {
                return moment(this.reply.created_at).fromNow() + '...';
            }
        },

        created () {
            window.events.$on('best-reply-selected', id => {
                this.isBest = (id === this.id);
            });
        },

        methods: {
            update() {
                axios.patch(
                    '/replies/' + this.id, {
                        body: this.body
                    })
                    .catch(error => {
                        flash(error.response.data, 'danger');
                    });

                this.editing = false;

                flash('Updated!');
            },

            cancel() {
                this.editing = false;

                this.body = this.reply.body;
            },

            destroy() {
                axios.delete('/replies/' + this.id);

                this.$emit('deleted', this.id);
            },

            markBestReply() {
                axios.post('/replies/' + this.id + '/best');

                window.events.$emit('best-reply-selected', this.id);
            }
        }
    }
</script>
