<script>
import Replies from "../components/Replies.vue";
import SubscribeButton from "../components/SubscribeButton.vue";
import Highlight from "../components/Highlight.vue";

export default {
    props: ["thread"],

    components: { Replies, SubscribeButton, Highlight },

    data() {
        return {
            repliesCount: this.thread.replies_count,
            locked: this.thread.locked,
            pinned: this.thread.pinned,
            title: this.thread.title,
            body: this.thread.body,
            form: {},
            editing: false,
            feedback: "",
            errors: false
        };
    },

    created() {
        this.resetForm();
    },

    watch: {
        editing(enabled) {
            if (enabled) {
                this.$modal.show("update-thread");
            } else {
                this.$modal.hide("update-thread");
            }
        }
    },

    methods: {
        toggleLock() {
            let uri = `/locked-threads/${this.thread.slug}`;

            axios[this.locked ? "delete" : "post"](uri);

            this.locked = !this.locked;
        },

        togglePin() {
            let uri = `/pinned-threads/${this.thread.slug}`;

            axios[this.pinned ? "delete" : "post"](uri);

            this.pinned = !this.pinned;
        },

        update() {
            let uri = `/threads/${this.thread.channel.slug}/${
                this.thread.slug
            }`;

            axios
                .patch(uri, this.form)
                .then(() => {
                    this.editing = false;
                    this.title = this.form.title;
                    this.body = this.form.body;

                    flash("Your thread has been updated.");
                })
                .catch(error => {
                    this.feedback = "Whoops, validation failed.";
                    this.errors = error.response.data.errors;
                });
        },

        resetForm() {
            this.form = {
                title: this.thread.title,
                body: this.thread.body
            };

            this.editing = false;

            this.$modal.hide("update-thread");
        }
    }
};
</script>
