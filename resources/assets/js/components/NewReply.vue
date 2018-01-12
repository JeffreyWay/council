<template>
    <div>
        <div v-if="signedIn">
            <div class="form-group">
                <wysiwyg name="body" v-model="body" placeholder="Have something to say?"></wysiwyg>
            </div>

            <button type="submit"
                    class="btn btn-default"
                    @click="addReply">Post</button>
        </div>

        <p class="text-center" v-else>
            Please <a href="/login">sign in</a> to participate in this
            discussion.
        </p>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                body: ''
            };
        },

        methods: {
            addReply() {
                axios.post(location.pathname + '/replies', { body: this.body })
                    .catch(error => {
                        flash(error.response.data, 'danger');
                    })
                    .then(({data}) => {
                        this.body = '';

                        flash('Your reply has been posted.');

                        this.$emit('created', data);
                    });
            }
        }
    }
</script>
