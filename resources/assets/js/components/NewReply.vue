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

        <div v-else-if="! active">
            <div class="alert alert-warning" role="alert">
                <p>You are unable to reply to threads as your account is currently suspended.</p>
                <p>Please <a href="mailto:suspension@example.com?Subject=Account%20suspension">Contact Support</a> for assistance.</p>
            </div>
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
    import 'jquery.caret';
    import 'at.js';

    export default {
        data() {
            return {
                body: ''
            };
        },

        computed: {
            confirmed() {
                return window.App.user.confirmed;
            },
            active() {
                return window.App.user.active;
            }
        },

        mounted() {
            $('#body').atwho({
                at: '@',
                delay: 750,
                callbacks: {
                    remoteFilter: function (query, callback) {
                        $.getJSON('/api/users', { name: query }, function (usernames) {
                            callback(usernames);
                        });
                    }
                }
            });
        },

        methods: {
            addReply() {
                axios.post(location.pathname + '/replies', { body: this.body })
                  .catch(error => {
                      flash(error.response.data.reason, 'danger');
                  })
                  .then(({ data }) => {
                      this.body = '';

                      flash('Your reply has been posted.');

                      this.$emit('created', data);
                  });
            }
        }
    };
</script>
