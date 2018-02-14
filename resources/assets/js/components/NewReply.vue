<template>
    <div class="py-6 ml-10">
        <div v-if="! signedIn">
            <p class="text-center text-sm text-grey-dark">
                Please <a href="/login" @click.prevent="$modal.show('login')" class="text-blue link">sign in</a> to participate in this
                discussion.
            </p>
        </div>

        <div v-else-if="! confirmed">
            To participate in this thread, please check your email and confirm your account.
        </div>

        <div v-else>
            <div class="mb-3">
                <wysiwyg name="body" v-model="body" placeholder="Have something to say?"></wysiwyg>
            </div>

            <button type="submit"
                    class="btn is-green"
                    @click="addReply">Post</button>
        </div>
    </div>
</template>

<script>
import "jquery.caret";
import "at.js";

export default {
    data() {
        return {
            body: ""
        };
    },

    computed: {
        confirmed() {
            return window.App.user.confirmed;
        }
    },

    mounted() {
        $("#body").atwho({
            at: "@",
            delay: 750,
            callbacks: {
                remoteFilter: function(query, callback) {
                    $.getJSON("/api/users", { name: query }, function(
                        usernames
                    ) {
                        callback(usernames);
                    });
                }
            }
        });
    },

    methods: {
        addReply() {
            axios
                .post(location.pathname + "/replies", { body: this.body })
                .catch(error => {
                    flash(error.response.data, "danger");
                })
                .then(({ data }) => {
                    this.body = "";

                    flash("Your reply has been posted.");

                    this.$emit("created", data);
                });
        },
        // wait until the user has finished typing before broadcasting
        typing: _.debounce(() => {
            window.Echo.join(`forum.${window.channelName}`).whisper('typing', {
              user: window.App.user
            });
          },
          // # of ms we wait for the user to stop typing.
          500
        )
    },

    watch: {
      body(val) {
        if (val === '') {
          return;
        }
        
        this.typing();
      }
    }
};
</script>

<style scoped>
.new-reply {
    background-color: #fff;
}
</style>
