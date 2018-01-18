<template>
    <div>
        <div v-if="! signedIn">
            <p class="text-center">
                Please <a href="/login">sign in</a> to participate in this
                discussion.
            </p>
        </div>

        <div v-else-if="! confirmed">
            To participate in this thread, please check your email and confirm your account.
        </div>

        <div v-else>
            <div class="form-group">
                <wysiwyg name="body" v-model="body" placeholder="Have something to say?"></wysiwyg>
            </div>

            <button type="submit"
                    class="btn btn-default"
                    @click="addReply">Post</button>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                body: ''
            };
        },

        computed: {
            confirmed() {
                return window.App.user.confirmed;
            }
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
